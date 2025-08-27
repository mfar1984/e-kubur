<?php

namespace App\Http\Controllers;

use App\Models\Kematian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Facades\Activity;

class KematianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $dariTarikh = request('dari_tarikh');
        $sehinggaTarikh = request('sehingga_tarikh');
        $user = Auth::user();

        $kematian = Kematian::query()
            ->search($search)
            ->filterByDateRange($dariTarikh, $sehinggaTarikh)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('kematian.index', compact('kematian', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        return view('kematian.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'tarikh_lahir' => 'required|date',
            'no_ic' => 'required|string|max:14|unique:kematian,no_ic',
            'tarikh_meninggal' => 'required|date|after_or_equal:tarikh_lahir',
            'longitude' => 'required|numeric|between:-180,180',
            'latitude' => 'required|numeric|between:-90,90',
            'waris' => 'required|string|max:255',
            'telefon_waris' => 'required|string|max:20',
            'catatan' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml|max:102400',
        ], [
            'nama.required' => 'Nama adalah wajib diisi.',
            'tarikh_lahir.required' => 'Tarikh lahir adalah wajib diisi.',
            'no_ic.required' => 'Nombor IC adalah wajib diisi.',
            'no_ic.unique' => 'Nombor IC ini sudah wujud dalam sistem.',
            'tarikh_meninggal.required' => 'Tarikh meninggal adalah wajib diisi.',
            'tarikh_meninggal.after_or_equal' => 'Tarikh meninggal mestilah selepas atau sama dengan tarikh lahir.',
            'longitude.required' => 'Longitude adalah wajib diisi.',
            'longitude.numeric' => 'Longitude mestilah nombor.',
            'longitude.between' => 'Longitude mestilah antara -180 hingga 180.',
            'latitude.required' => 'Latitude adalah wajib diisi.',
            'latitude.numeric' => 'Latitude mestilah nombor.',
            'latitude.between' => 'Latitude mestilah antara -90 hingga 90.',
            'waris.required' => 'Nama waris adalah wajib diisi.',
            'telefon_waris.required' => 'Nombor telefon waris adalah wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $kematian = Kematian::create([
            'nama' => $request->nama,
            'tarikh_lahir' => $request->tarikh_lahir,
            'no_ic' => $request->no_ic,
            'tarikh_meninggal' => $request->tarikh_meninggal,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'waris' => $request->waris,
            'telefon_waris' => $request->telefon_waris,
            'catatan' => $request->catatan,
            'created_by' => Auth::id(),
        ]);

        // Log create with IP/UA
        activity('kematian')
            ->event('created')
            ->causedBy(Auth::user())
            ->performedOn($kematian)
            ->withProperties([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Rekod kematian dicipta');

        // Save attachments (if any)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (!$file) continue;
                $path = $file->store('kematian/'.$kematian->id, 'public');
                \App\Models\KematianAttachment::create([
                    'kematian_id' => $kematian->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size_bytes' => $file->getSize(),
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('kematian.index')
            ->with('success', 'Rekod kematian berjaya ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kematian $kematian)
    {
        $user = Auth::user();
        return view('kematian.show', compact('kematian', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kematian $kematian)
    {
        $user = Auth::user();
        return view('kematian.edit', compact('kematian', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kematian $kematian)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'tarikh_lahir' => 'required|date',
            'no_ic' => 'required|string|max:14|unique:kematian,no_ic,' . $kematian->id,
            'tarikh_meninggal' => 'required|date|after_or_equal:tarikh_lahir',
            'longitude' => 'required|numeric|between:-180,180',
            'latitude' => 'required|numeric|between:-90,90',
            'waris' => 'required|string|max:255',
            'telefon_waris' => 'required|string|max:20',
            'catatan' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml|max:102400',
        ], [
            'nama.required' => 'Nama adalah wajib diisi.',
            'tarikh_lahir.required' => 'Tarikh lahir adalah wajib diisi.',
            'no_ic.required' => 'Nombor IC adalah wajib diisi.',
            'no_ic.unique' => 'Nombor IC ini sudah wujud dalam sistem.',
            'tarikh_meninggal.required' => 'Tarikh meninggal adalah wajib diisi.',
            'tarikh_meninggal.after_or_equal' => 'Tarikh meninggal mestilah selepas atau sama dengan tarikh lahir.',
            'longitude.required' => 'Longitude adalah wajib diisi.',
            'longitude.numeric' => 'Longitude mestilah nombor.',
            'longitude.between' => 'Longitude mestilah antara -180 hingga 180.',
            'latitude.required' => 'Latitude adalah wajib diisi.',
            'latitude.numeric' => 'Latitude mestilah nombor.',
            'latitude.between' => 'Latitude mestilah antara -90 hingga 90.',
            'waris.required' => 'Nama waris adalah wajib diisi.',
            'telefon_waris.required' => 'Nombor telefon waris adalah wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $kematian->update([
            'nama' => $request->nama,
            'tarikh_lahir' => $request->tarikh_lahir,
            'no_ic' => $request->no_ic,
            'tarikh_meninggal' => $request->tarikh_meninggal,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'waris' => $request->waris,
            'telefon_waris' => $request->telefon_waris,
            'catatan' => $request->catatan,
            'updated_by' => Auth::id(),
        ]);

        // Log update with IP/UA
        activity('kematian')
            ->event('updated')
            ->causedBy(Auth::user())
            ->performedOn($kematian)
            ->withProperties([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Rekod kematian dikemaskini');

        // New attachments on edit
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (!$file) continue;
                $path = $file->store('kematian/'.$kematian->id, 'public');
                \App\Models\KematianAttachment::create([
                    'kematian_id' => $kematian->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size_bytes' => $file->getSize(),
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('kematian.index')
            ->with('success', 'Rekod kematian berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kematian $kematian)
    {
        // Log before delete to capture subject
        activity('kematian')
            ->event('deleted')
            ->causedBy(Auth::user())
            ->performedOn($kematian)
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Rekod kematian dipadamkan');

        $kematian->delete();

        return redirect()->route('kematian.index')
            ->with('success', 'Rekod kematian berjaya dipadamkan.');
    }

    /**
     * Export death registry data
     */
    public function export()
    {
        $kematian = Kematian::orderBy('created_at', 'desc')->get();

        $filename = 'daftar_kematian_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($kematian) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'Nama',
                'Tarikh Lahir',
                'No. IC',
                'Tarikh Meninggal',
                'Longitude',
                'Latitude',
                'Waris',
                'Telefon Waris',
                'Tarikh Dicipta',
                'Tarikh Kemaskini'
            ]);

            // Data
            foreach ($kematian as $record) {
                fputcsv($file, [
                    $record->nama,
                    $record->tarikh_lahir ? $record->tarikh_lahir->format('d/m/Y') : '',
                    $record->no_ic,
                    $record->tarikh_meninggal ? $record->tarikh_meninggal->format('d/m/Y') : '',
                    $record->longitude,
                    $record->latitude,
                    $record->waris,
                    $record->telefon_waris,
                    $record->created_at ? $record->created_at->format('d/m/Y H:i') : '',
                    $record->updated_at ? $record->updated_at->format('d/m/Y H:i') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Search kematian records for public API
     */
    public function search(Request $request)
    {
        try {
            $search = $request->get('q', '');
            $type = $request->get('type', 'nama'); // 'nama' or 'ic'
            
            // Validate search term
            if (strlen($search) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sila masukkan sekurang-kurangnya 2 aksara untuk carian.',
                    'data' => []
                ], 400);
            }

            $query = Kematian::query();

            // Search by name or IC
            if ($type === 'ic') {
                $query->where('no_ic', 'LIKE', "%{$search}%");
            } else {
                $query->where('nama', 'LIKE', "%{$search}%");
            }

            $results = $query->orderBy('tarikh_meninggal', 'desc')
                           ->limit(50) // Limit results for performance
                           ->get();

            // Format results for public display
            $formattedResults = $results->map(function ($kematian) {
                return [
                    'id' => $kematian->id,
                    'nama' => $kematian->nama,
                    'no_ic' => $kematian->no_ic,
                    'tarikh_meninggal' => $kematian->tarikh_meninggal ? $kematian->tarikh_meninggal->format('Y-m-d') : null,
                    'tarikh_meninggal_formatted' => $this->formatDateMalay($kematian->tarikh_meninggal),
                    'tarikh_hijri' => $this->formatHijriMalay($kematian->tarikh_meninggal),
                    'latitude' => $kematian->latitude,
                    'longitude' => $kematian->longitude,
                    'waris' => $kematian->waris,
                    'telefon_waris' => $this->maskPhone($kematian->telefon_waris),
                    'honorific' => $this->getHonorific($kematian->nama, $kematian->no_ic)
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Carian berjaya.',
                'data' => $formattedResults,
                'total' => $results->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat dalaman. Sila cuba lagi.',
                'data' => []
            ], 500);
        }
    }

    /**
     * Format date to Malay full day and month
     */
    private function formatDateMalay($date)
    {
        if (!$date) return '-';
        $timestamp = strtotime($date);
        $hari = ['Ahad','Isnin','Selasa','Rabu','Khamis','Jumaat','Sabtu'];
        $bulan = [1=>'Januari','Februari','Mac','April','Mei','Jun','Julai','Ogos','September','Oktober','November','Disember'];
        $namaHari = $hari[(int)date('w', $timestamp)];
        $namaBulan = $bulan[(int)date('n', $timestamp)];
        return $namaHari . ', ' . date('d', $timestamp) . ' ' . $namaBulan . ' ' . date('Y', $timestamp);
    }

    /**
     * Convert Gregorian date to Hijri date in Malay
     */
    private function formatHijriMalay($date)
    {
        if (!$date) return '-';
        $ts = strtotime($date);
        $gYear = (int)date('Y', $ts);
        $gMonth = (int)date('n', $ts);
        $gDay = (int)date('j', $ts);

        $bulanHijri = [
            1 => 'Muharram', 2 => 'Safar', 3 => 'Rabiulawal', 4 => 'Rabiulakhir',
            5 => 'Jamadilawwal', 6 => 'Jamadilakhir', 7 => 'Rejab', 8 => 'Syaaban',
            9 => 'Ramadan', 10 => 'Syawal', 11 => 'Zulkaedah', 12 => 'Zulhijjah',
        ];

        if (function_exists('gregoriantojd') && function_exists('cal_from_jd') && defined('CAL_HIJRI')) {
            $jd = gregoriantojd($gMonth, $gDay, $gYear);
            $h = cal_from_jd($jd, CAL_HIJRI);
            $hDay = (int)$h['day'];
            $hMonth = (int)$h['month'];
            $hYear = (int)$h['year'];
            $namaBulan = isset($bulanHijri[$hMonth]) ? $bulanHijri[$hMonth] : $hMonth;
            return sprintf('%d %s %d', $hDay, $namaBulan, $hYear);
        }

        // Fallback approximation
        $m = $gMonth; $d = $gDay; $y = $gYear;
        $jd = (int)( (1461 * ($y + 4800 + (int)(($m - 14)/12)))/4 + (367 * ($m - 2 - 12 * (int)(($m - 14)/12)))/12 - (3 * (int)((($y + 4900 + (int)(($m - 14)/12))/100)))/4 + $d - 32075 );
        $l = $jd - 1948440 + 10632;
        $n = (int)(($l - 1) / 10631);
        $l = $l - 10631 * $n + 354;
        $j = (int)((10985 - $l) / 5316) * (int)((50 * $l) / 17719) + (int)($l / 5670) * (int)((43 * $l) / 15238);
        $l = $l - (int)((30 - $j) / 15) * (int)((17719 * $j) / 50) - (int)($j / 16) * (int)((15238 * $j) / 43) + 29;
        $hMonth = (int)( (24 * $l) / 709 );
        $hDay = (int)($l - (int)((709 * $hMonth) / 24));
        $hYear = 30 * $n + $j - 30;
        $namaBulan = isset($bulanHijri[$hMonth]) ? $bulanHijri[$hMonth] : $hMonth;
        return sprintf('%d %s %d', $hDay, $namaBulan, $hYear);
    }

    /**
     * Mask phone number for privacy
     */
    private function maskPhone($phone)
    {
        $digits = preg_replace('/\D/', '', (string)$phone);
        if (strlen($digits) <= 4) { return htmlspecialchars($phone); }
        // More secure: show only first 2 and last 2 digits
        $masked = substr($digits, 0, 2) . str_repeat('*', max(0, strlen($digits) - 4)) . substr($digits, -2);
        return $masked;
    }

    /**
     * Determine honorific based on name or IC
     */
    private function getHonorific($name, $noIc)
    {
        $n = strtolower(' ' . (string)$name . ' ');
        if (strpos($n, ' binti ') !== false) {
            return 'Almarhumah';
        }
        if (strpos($n, ' bin ') !== false) {
            return 'Almarhum';
        }
        $digits = preg_replace('/\D+/', '', (string)$noIc);
        if ($digits !== '' ) {
            $last = (int)substr($digits, -1);
            if ($last % 2 === 0) {
                return 'Almarhumah';
            } else {
                return 'Almarhum';
            }
        }
        return 'Almarhum';
    }

}
