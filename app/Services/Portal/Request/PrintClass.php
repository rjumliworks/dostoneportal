<?php

namespace App\Services\Portal\Request;
use Hashids\Hashids;
use App\Models\OrgChart;
use App\Models\ListLeave;
use App\Models\ListDropdown;
use App\Models\RequestReport;
use App\Models\RequestSignatory;
use App\Models\RequestTag;
use App\Services\Common\Signing\SignatureResolver;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class PrintClass
{
    public function document($request)
    {
        $hashids = new Hashids('krad', 10);
        $id = $hashids->decode($request->key);

        $data = RequestReport::where('request_id', $id)->value('information');
        $data = json_decode($data,true);
        $this->refreshSignatures($data, $id[0] ?? $id);
        $this->embedSignatures($data);
        $url = request()->getSchemeAndHttpHost() . '/verification/' . $request->key;
        $result = new Builder(
            writer: new PngWriter(),
            data: $url,
            size: 300,
            margin: 10,
        );

        $qrCodeImageString = $result->build()->getString();
        $base64Image = 'data:image/png;base64,' . base64_encode($qrCodeImageString);

        $array = [
            'qrCodeImage' => $base64Image,
            'data' => $data
        ];
// dd($array);
        switch($request->type){
            case 'Render Overtime Service':
                $a = OrgChart::with('user.profile','oic.profile')->where('designation_id',48)->where('is_active',1)->first(); 

                $array['signatory']['cto'] = [
                    'name' => ($a->is_oic) ? $a->oic->profile->fullname : $a->user->profile->fullname,    
                    'role' => 'Human Resource Management Officer'
                ];
                $pdf = \PDF::loadView('reports.overtime', $array)->setPaper('a4', 'portrait');
            break;
            case 'Leave Form':
                $array['types'] = ListLeave::where('is_active',1)->get(); //->where('is_regular',1)->take(12)
                $array['titles'] = ListDropdown::where('classification','Leave Details')->where('type','!=','Absent')->select('type')->distinct()->get();
                $array['details'] = ListDropdown::where('classification','Leave Details')->get();
                $a = OrgChart::with('user.profile', 'oic.profile')
                    ->where('designation_id', 48)
                    ->where('is_active', 1)
                    ->first();

                if ($a && $a->user && $a->user->profile) {
                    $array['signatory']['certified'] = [
                        'name' => $a->user->profile->fullname,
                        'role' => null,
                    ];
                }
                $pdf = \PDF::loadView('reports.leave', $array)->setPaper('a4', 'portrait');
            break;
            case 'Travel Order':
                // Only print the division page for the logged-in user's own tag on this
                // request, instead of every division's page. Falls back to the full
                // multi-division document when the current user isn't a tagged employee
                // (e.g. the requester filing on someone else's behalf).
                $currentTag = RequestTag::where('request_id', $id)->where('user_id', auth()->id())->first();
                if ($currentTag && !empty($array['data']['signatories'])) {
                    $array['data']['signatories'] = collect($array['data']['signatories'])
                        ->filter(fn($sign) => $sign['division_id'] == $currentTag->division_id)
                        ->values()
                        ->all();
                }

                $pdf = \PDF::loadView('reports.travel', $array)->setPaper('a4', 'portrait');
            break;
            case 'Vehicle Reservation':

            break;
        }

        $pdf->output();
        $dompdf = $pdf->getDomPDF();

        $canvas = $dompdf->getCanvas();
        // $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        //     $text = "PAGE $pageNumber OF $pageCount";
        //     $font = $fontMetrics->get_font("Helvetica", "normal");
        //     $canvas->text(63.5, 796, $text, $font, 7);
        // });

        $pdfBinary = $dompdf->output();
        $secret = config('app.key');
        $hmac = hash_hmac('sha256', $pdfBinary, $secret);
        $meta = "\n%--- DOC META ---\n";
        $meta .= "% ValidationHMAC: {$hmac}\n";
        $meta .= "% GeneratedAt: " . now()->toDateTimeString() . "\n";
        $meta .= "%--- END META ---\n";

        $pos = strrpos($pdfBinary, '%%EOF');
        if ($pos !== false) {
            $pdfBinary = substr_replace($pdfBinary, $meta . '%%EOF', $pos, 5);
        } else {
            $pdfBinary .= $meta . "%%EOF\n";
        }

        return response($pdfBinary)
            ->header('Content-Type', 'application/pdf')
             ->header('Content-Disposition', 'inline; filename="' . $data['code'] . '.pdf"');
    }

    // Older RequestReport snapshots were written before signatures moved to
    // user_certificates and still hold the legacy local filenames (or none at
    // all). Rather than trust the frozen snapshot, look up each signatory's
    // current certificate signature fresh so already-approved requests print
    // correctly too, without needing to be re-actioned.
    private function refreshSignatures(&$data, $requestId)
    {
        if (!is_array($data)) {
            return;
        }

        if (!empty($data['employee']) && is_array($data['employee'])) {
            $tag = RequestTag::where('request_id', $requestId)->with('user.certificate')->first();
            if ($tag) {
                $data['employee']['signature'] = $tag->user?->certificate?->signature;
            }
        }

        if (empty($data['signatories']) || !is_array($data['signatories'])) {
            return;
        }

        $live = RequestSignatory::where('request_id', $requestId)
            ->with('recommended.user.certificate', 'approved.user.certificate')
            ->get()
            ->keyBy('code');

        foreach ($data['signatories'] as &$sign) {
            $signatory = $live->get($sign['code'] ?? null);
            if (!$signatory) {
                continue;
            }
            if (isset($sign['recommended'])) {
                $sign['recommended']['signature'] = $signatory->recommended?->user?->certificate?->signature;
            }
            if (isset($sign['approved'])) {
                $sign['approved']['signature'] = $signatory->approved?->user?->certificate?->signature;
            }
        }
        unset($sign);
    }

    // Stored reports keep the raw S3 key under any "signature" leaf. Dompdf can't
    // fetch remote images (enable_remote is off), so resolve every one of those
    // keys to an embeddable base64 data URI right before the view renders.
    private function embedSignatures(&$data)
    {
        if (!is_array($data)) {
            return;
        }

        $resolver = new SignatureResolver();
        array_walk_recursive($data, function (&$value, $key) use ($resolver) {
            if ($key === 'signature' && is_string($value) && $value !== '' && !str_starts_with($value, 'data:')) {
                $value = $resolver->resolvePath($value);
            }
        });
    }

    // private function signatory($divisions){
    //     $a = OrgChart::with('user.profile','oic.profile')->where('designation_id',43)->where('is_active',1)->first(); 
    //     $approved = [
    //         'name' => ($a->is_oic) ? $a->oic->profile->fullname : $a->user->profile->fullname,    
    //         'role' => ($a->is_oic) ? 'OIC - Regional Director' : 'Regional Director'
    //     ];
    //     foreach($divisions as $division){
    //         $d = OrgChart::with('user.profile','oic.profile','assigned')
    //         ->whereHas('assigned', function ($query) use ($division){
    //             $query->where('name', $division);
    //         })
    //         ->where('designation_id',44)->where('is_active',1)->first(); 
    //         if ($d) {
    //             $assigned = $d->assigned->others ?? '';
    //             $recommended[] = [
    //                 'name' => ($d->is_oic) ? $d->oic->profile->fullname : $d->user->profile->fullname,
    //                 'role' => ($d->is_oic) ? 'OIC - Assistant Regional Director (' . $assigned . ')' : 'Assistant Regional Director (' . $assigned . ')'
    //             ];
    //         } else {
    //             $recommended[] = [
    //                 'name' => '',
    //                 'role' => ''
    //             ];
    //         }
    //     }
    //     return [
    //         'approved' => $approved,
    //         'recommended' => $recommended
    //     ];
    // }
}
