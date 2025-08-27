<?php

namespace App\Http\Controllers;

use App\Models\Kematian;
use App\Models\KematianAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KematianAttachmentController extends Controller
{
    public function index(Kematian $kematian)
    {
        $attachments = $kematian->attachments()->orderBy('created_at','desc')->get();
        return response()->json(['success'=>true,'data'=>$attachments->map(function($a){
            return [
                'id'=>$a->id,
                'filename'=>$a->filename,
                'url'=>Storage::disk('public')->url($a->path),
                'mime_type'=>$a->mime_type,
                'size_bytes'=>$a->size_bytes,
                'created_at'=>$a->created_at?->toIso8601String(),
            ];
        })]);
    }

    public function store(Request $request, Kematian $kematian)
    {
        try {
            $request->validate([
                'photo' => 'required|file|mimes:jpg,jpeg,png,gif,svg|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml|max:102400', // 100MB
            ]);

            $file = $request->file('photo');
            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiada fail yang dipilih'
                ], 400);
            }

            $dir = 'kematian/'.$kematian->id;
            $storedPath = $file->store($dir, 'public');

            if (!$storedPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan fail'
                ], 500);
            }

            $att = KematianAttachment::create([
                'kematian_id' => $kematian->id,
                'filename' => $file->getClientOriginalName(),
                'path' => $storedPath,
                'mime_type' => $file->getClientMimeType(),
                'size_bytes' => $file->getSize(),
                'uploaded_by' => Auth::id(),
            ]);

            // Log the upload with IP address and User Agent
            activity('audit')
                ->event('created')
                ->performedOn($att)
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'kematian_id' => $kematian->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $storedPath,
                    'size_bytes' => $file->getSize()
                ])
                ->log('Lampiran gambar dimuat naik');

            $imageUrl = asset('storage/' . $att->path);
            \Log::info('Generated image URL: ' . $imageUrl . ' for path: ' . $att->path);
            
            return response()->json([
                'success' => true,
                'message' => 'Gambar berjaya dimuat naik',
                'data' => [
                    'id' => $att->id,
                    'filename' => $att->filename,
                    'url' => $imageUrl,
                    'path' => $att->path,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Attachment upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ralat sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Kematian $kematian, KematianAttachment $attachment)
    {
        try {
            if ($attachment->kematian_id !== $kematian->id) {
                return response()->json(['success' => false, 'message' => 'Rekod tidak sepadan'], 400);
            }

            // Log the deletion with IP address and User Agent
            activity('audit')
                ->event('deleted')
                ->performedOn($attachment)
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'kematian_id' => $kematian->id,
                    'filename' => $attachment->filename,
                    'path' => $attachment->path
                ])
                ->log('Lampiran gambar dipadamkan');

            if ($attachment->path && Storage::disk('public')->exists($attachment->path)) {
                Storage::disk('public')->delete($attachment->path);
            }
            
            $attachment->delete();

            return response()->json(['success' => true, 'message' => 'Gambar dipadam']);

        } catch (\Exception $e) {
            \Log::error('Attachment delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ralat sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}


