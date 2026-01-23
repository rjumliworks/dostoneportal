<?php

namespace App\Services\Portal\Request;
use Hashids\Hashids;
use App\Models\RequestReport;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class PrintClass
{
   public function overtime($request)
    {
        $hashids = new Hashids('krad', 10);
        $id = $hashids->decode($request->key);

        $data = RequestReport::where('request_id', $id)->value('information');
        $data = json_decode($data,true);
        $url = $_SERVER['HTTP_HOST'] . '/verification/' . $request->key;
        $qrCode = new QrCode($url);
        $qrCode->setSize(300);
        $pngWriter = new PngWriter();
        $qrCodeImageString = $pngWriter->write($qrCode)->getString();
        $base64Image = 'data:image/png;base64,' . base64_encode($qrCodeImageString);

        $array = [
            'qrCodeImage' => $base64Image,
            'data' => $data
        ];

        $pdf = \PDF::loadView('reports.overtime', $array)->setPaper('a4', 'portrait');
        $pdf->output();
        $dompdf = $pdf->getDomPDF();

        // Add page numbering
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "PAGE $pageNumber OF $pageCount";
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $canvas->text(63.5, 796, $text, $font, 7);
        });

        // Generate clean binary
        $pdfBinary = $dompdf->output();

        // Compute HMAC on clean version
        $secret = config('app.key');
        $hmac = hash_hmac('sha256', $pdfBinary, $secret);

        // Embed metadata AFTER computing HMAC and signature
        $meta = "\n%--- DOC META ---\n";
        $meta .= "% ValidationHMAC: {$hmac}\n";
        $meta .= "% GeneratedAt: " . now()->toDateTimeString() . "\n";
        $meta .= "%--- END META ---\n";

        // Insert before final %%EOF
        $pos = strrpos($pdfBinary, '%%EOF');
        if ($pos !== false) {
            $pdfBinary = substr_replace($pdfBinary, $meta . '%%EOF', $pos, 5);
        } else {
            $pdfBinary .= $meta . "%%EOF\n";
        }

        return response($pdfBinary)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="test.pdf"');
    }

}
