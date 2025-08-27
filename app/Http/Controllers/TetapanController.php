<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tetapan;

class TetapanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Tetapan::query();
        
        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Filter by category
        if ($request->filled('kategori')) {
            $query->filterByKategori($request->kategori);
        }
        
        // Filter by type
        if ($request->filled('jenis')) {
            $query->filterByJenis($request->jenis);
        }
        
        $tetapan = $query->ordered()->paginate(15);
        
        // Get available categories and types for filters
        $kategori = Tetapan::distinct()->pluck('kategori');
        $jenis = Tetapan::distinct()->pluck('jenis');
        
        return view('tetapan.index', compact('tetapan', 'kategori', 'jenis', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        return view('tetapan.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kunci' => 'required|string|max:255|unique:tetapan,kunci',
            'nama' => 'required|string|max:255',
            'nilai' => 'required',
            'jenis' => 'required|in:text,number,boolean,email,date,file',
            'penerangan' => 'nullable|string',
            'boleh_edit' => 'boolean',
            'kategori' => 'required|string|max:255',
            'susunan' => 'nullable|integer|min:0',
        ]);

        $tetapan = new Tetapan($request->all());
        $tetapan->created_by = Auth::id();
        $tetapan->updated_by = Auth::id();
        $tetapan->save();

        return redirect()->route('tetapan.index')->with('success', 'Tetapan berjaya ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tetapan $tetapan)
    {
        $user = Auth::user();
        return view('tetapan.show', compact('tetapan', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tetapan $tetapan)
    {
        $user = Auth::user();
        return view('tetapan.edit', compact('tetapan', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tetapan $tetapan)
    {
        $request->validate([
            'kunci' => 'required|string|max:255|unique:tetapan,kunci,' . $tetapan->id,
            'nama' => 'required|string|max:255',
            'nilai' => 'required',
            'jenis' => 'required|in:text,number,boolean,email,date,file',
            'penerangan' => 'nullable|string',
            'boleh_edit' => 'boolean',
            'kategori' => 'required|string|max:255',
            'susunan' => 'nullable|integer|min:0',
        ]);

        $tetapan->update($request->all());
        $tetapan->updated_by = Auth::id();
        $tetapan->save();

        return redirect()->route('tetapan.index')->with('success', 'Tetapan berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tetapan $tetapan)
    {
        if (!$tetapan->boleh_edit) {
            return redirect()->route('tetapan.index')->with('error', 'Tetapan ini tidak boleh dipadamkan.');
        }

        $tetapan->delete();
        return redirect()->route('tetapan.index')->with('success', 'Tetapan berjaya dipadamkan.');
    }

    /**
     * Bulk update tetapan
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'tetapan' => 'required|array',
            'tetapan.*' => 'nullable',
        ]);

        foreach ($request->tetapan as $kunci => $nilai) {
            if ($kunci && $nilai !== null) {
                // Handle boolean values
                if (in_array($kunci, ['notify_new_user', 'notify_login_failed', 'notify_system_error'])) {
                    $nilai = (bool) $nilai;
                }
                
                // Handle number values
                if (in_array($kunci, ['max_login_attempts', 'session_timeout', 'default_latitude', 'default_longitude'])) {
                    $nilai = (float) $nilai;
                }
                
                Tetapan::updateOrCreate(
                    ['kunci' => $kunci],
                    [
                        'nilai' => $nilai,
                        'updated_by' => Auth::id(),
                    ]
                );
            }
        }

        return redirect()->route('tetapan.index')->with('success', 'Tetapan berjaya dikemaskini.');
    }

    /**
     * Export tetapan to CSV
     */
    public function export(Request $request)
    {
        $query = Tetapan::query();
        
        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('kategori')) {
            $query->filterByKategori($request->kategori);
        }
        
        if ($request->filled('jenis')) {
            $query->filterByJenis($request->jenis);
        }
        
        $tetapan = $query->ordered()->get();
        
        $filename = 'tetapan_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($tetapan) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Kunci',
                'Nama',
                'Nilai',
                'Jenis',
                'Kategori',
                'Penerangan',
                'Boleh Edit',
                'Susunan',
                'Dicipta Pada',
                'Dikemaskini Pada'
            ]);
            
            foreach ($tetapan as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->kunci,
                    $item->nama,
                    $item->nilai,
                    $item->jenis,
                    $item->kategori,
                    $item->penerangan,
                    $item->boleh_edit ? 'Ya' : 'Tidak',
                    $item->susunan,
                    $item->created_at->format('d/m/Y H:i'),
                    $item->updated_at->format('d/m/Y H:i'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
