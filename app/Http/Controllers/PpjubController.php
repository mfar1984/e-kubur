<?php

namespace App\Http\Controllers;

use App\Models\Ppjub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Facades\Activity;

class PpjubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $email = request('email');
        $status = request('status');
        $user = Auth::user();

        $ppjub = Ppjub::query()
            ->search($search)
            ->filterByEmail($email)
            ->filterByStatus($status)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('ppjub.index', compact('ppjub', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        return view('ppjub.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_ic' => 'required|string|max:14|unique:ppjub,no_ic',
            'telefon' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:ppjub,email',
            'alamat' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'tarikh_keahlian' => 'required|date',
        ], [
            'nama.required' => 'Nama adalah wajib diisi.',
            'no_ic.required' => 'Nombor IC adalah wajib diisi.',
            'no_ic.unique' => 'Nombor IC ini sudah wujud dalam sistem.',
            'telefon.required' => 'Nombor telefon adalah wajib diisi.',
            'email.required' => 'Emel adalah wajib diisi.',
            'email.email' => 'Format emel tidak sah.',
            'email.unique' => 'Emel ini sudah wujud dalam sistem.',
            'alamat.required' => 'Alamat adalah wajib diisi.',
            'status.required' => 'Status adalah wajib diisi.',
            'tarikh_keahlian.required' => 'Tarikh keahlian adalah wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ppjub = Ppjub::create([
            'nama' => $request->nama,
            'no_ic' => $request->no_ic,
            'telefon' => $request->telefon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'status' => $request->status,
            'tarikh_keahlian' => $request->tarikh_keahlian,
            'created_by' => Auth::id(),
        ]);

        // Log create with IP/UA
        activity('ppjub')
            ->event('created')
            ->causedBy(Auth::user())
            ->performedOn($ppjub)
            ->withProperties([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Ahli PPJUB dicipta');

        return redirect()->route('ppjub.index')
            ->with('success', 'Ahli PPJUB berjaya ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ppjub $ppjub)
    {
        $user = Auth::user();
        return view('ppjub.show', compact('ppjub', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ppjub $ppjub)
    {
        $user = Auth::user();
        return view('ppjub.edit', compact('ppjub', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ppjub $ppjub)
    {
        // Normalize inputs (trim whitespace)
        $request->merge([
            'nama' => trim((string) $request->nama),
            'no_ic' => preg_replace('/\s+/', '', (string) $request->no_ic),
            'email' => trim((string) $request->email),
        ]);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_ic' => [
                'required',
                'string',
                'max:14',
                Rule::unique('ppjub', 'no_ic')->ignore($ppjub->id),
            ],
            'telefon' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('ppjub', 'email')->ignore($ppjub->id),
            ],
            'alamat' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'tarikh_keahlian' => 'required|date',
        ], [
            'nama.required' => 'Nama adalah wajib diisi.',
            'no_ic.required' => 'Nombor IC adalah wajib diisi.',
            'no_ic.unique' => 'Nombor IC ini sudah wujud dalam sistem.',
            'telefon.required' => 'Nombor telefon adalah wajib diisi.',
            'email.required' => 'Emel adalah wajib diisi.',
            'email.email' => 'Format emel tidak sah.',
            'email.unique' => 'Emel ini sudah wujud dalam sistem.',
            'alamat.required' => 'Alamat adalah wajib diisi.',
            'status.required' => 'Status adalah wajib diisi.',
            'tarikh_keahlian.required' => 'Tarikh keahlian adalah wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ppjub->update([
            'nama' => $request->nama,
            'no_ic' => $request->no_ic,
            'telefon' => $request->telefon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'status' => $request->status,
            'tarikh_keahlian' => $request->tarikh_keahlian,
            'updated_by' => Auth::id(),
        ]);

        // Log update with IP/UA
        activity('ppjub')
            ->event('updated')
            ->causedBy(Auth::user())
            ->performedOn($ppjub)
            ->withProperties([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Ahli PPJUB dikemaskini');

        return redirect()->route('ppjub.index')
            ->with('success', 'Ahli PPJUB berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ppjub $ppjub)
    {
        $ppjub->delete();

        // Log delete with IP/UA
        activity('ppjub')
            ->event('deleted')
            ->causedBy(Auth::user())
            ->performedOn($ppjub)
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Ahli PPJUB dipadamkan');

        return redirect()->route('ppjub.index')
            ->with('success', 'Ahli PPJUB berjaya dipadamkan.');
    }

    /**
     * Export PPJUB data
     */
    public function export()
    {
        $ppjub = Ppjub::orderBy('created_at', 'desc')->get();

        $filename = 'ahli_ppjub_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($ppjub) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'Nama',
                'No. IC',
                'Telefon',
                'Email',
                'Alamat',
                'Status',
                'Tarikh Keahlian',
                'Tarikh Dicipta',
                'Tarikh Kemaskini'
            ]);

            // Data
            foreach ($ppjub as $member) {
                fputcsv($file, [
                    $member->nama,
                    $member->no_ic,
                    $member->telefon,
                    $member->email,
                    $member->alamat,
                    $member->status,
                    $member->tarikh_keahlian ? $member->tarikh_keahlian->format('d/m/Y') : '',
                    $member->created_at ? $member->created_at->format('d/m/Y H:i') : '',
                    $member->updated_at ? $member->updated_at->format('d/m/Y H:i') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}