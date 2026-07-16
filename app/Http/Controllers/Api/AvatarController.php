<?php

namespace App\Http\Controllers\Api;

use Hashids\Hashids;
use App\Models\Participant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function completed(Request $request){
        $data = Participant::where('id',$request->id)->first();
        $data->is_completed = 1;
        $data->save();
        $data->refresh();

        return response()->json([
            'status'  => true,
            'message' => 'Completed updated successfully',
            'data'    => $data->is_completed
        ]);
    }

    public function avatar(Request $request){

        try {
            $request->validate([
                'image' => 'required|image|max:2048', // Assuming maximum file size is 2MB
            ]);
           
                $participant = Participant::with('detail')->findOrFail($request->id);

                // Delete old avatar if exists
                if ($participant->detail->avatar) {
                    Storage::disk('public')->delete($participant->detail->avatar);
                }
                $timestamp = time();
                $extension = $request->file('image')->getClientOriginalExtension();
                $filename = $timestamp.$request->id . '.' . $extension;
                $path = $request->file('image')->storeAs('images/avatars', $filename, 'public');

                $participant->detail->avatar = $path;
                $participant->detail->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Profile updated successfully',
                    'data'    => asset('storage/'.$participant->detail->avatar)
                ]);

        }catch(\Throwable $th){

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function signature(Request $request){

        try {
            $request->validate([
                'signature' => 'required|image|max:2048', // Assuming maximum file size is 2MB
            ]);
           
                $participant = Participant::with('detail')->findOrFail($request->id);

                // Delete old avatar if exists
                if ($participant->detail->signature) {
                    Storage::disk('public')->delete('signatures/' . $participant->detail->signature);
                }

                $hashids = new Hashids('krad',10);
                $key = $hashids->encode($request->id);
                // Store new image
                $extension = $request->file('signature')->getClientOriginalExtension();
                $filename  = $key . '.' . $extension;
                $path = $request->file('signature')->storeAs('images/signatures', $filename, 'public');

                $participant->detail->signature = $path;
                $participant->detail->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Profile updated successfully',
                    'data'    => $this->convertToBase64($participant->detail->signature)
                ]);

        }catch(\Throwable $th){

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    private function convertToBase64($path)
    {
        // If you store public files like: storage/app/public/signatures/filename.png
        // and you saved the DB value like: signatures/filename.png
        if (Storage::disk('public')->exists($path)) {
            $file = Storage::disk('public')->get($path);
            $mime = Storage::disk('public')->mimeType($path);
            return 'data:' . $mime . ';base64,' . base64_encode($file);
        }

        // If you stored a full URL instead of a storage path:
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            try {
                $file = file_get_contents($path);
                $mime = @mime_content_type($path) ?: 'image/png';
                return 'data:' . $mime . ';base64,' . base64_encode($file);
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }
}
