<?php

namespace App\Http\Controllers;

use App\Models\Kematian;
use App\Models\Ppjub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OverviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get real statistics from database
        $totalPpjub = Ppjub::count();
        $totalKematian = Kematian::count();
        $totalWaris = Kematian::count(); // Each death record has one waris
        
        // Calculate monthly growth for PPJUB
        $lastMonthPpjub = Ppjub::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
        $currentMonthPpjub = Ppjub::whereMonth('created_at', Carbon::now()->month)->count();
        $ppjubGrowth = $lastMonthPpjub > 0 ? round((($currentMonthPpjub - $lastMonthPpjub) / $lastMonthPpjub) * 100) : 0;
        
        // Calculate monthly growth for Kematian based on tarikh_meninggal
        $lastMonthKematian = Kematian::whereMonth('tarikh_meninggal', Carbon::now()->subMonth()->month)->count();
        $currentMonthKematian = Kematian::whereMonth('tarikh_meninggal', Carbon::now()->month)->count();
        $kematianGrowth = $lastMonthKematian > 0 ? round((($currentMonthKematian - $lastMonthKematian) / $lastMonthKematian) * 100) : 0;
        
        // Get recent activities
        $recentPpjub = Ppjub::latest()->first();
        $recentKematian = Kematian::latest()->first();
        
        // Get recent death records for Daftar Kematian tab
        $recentDeaths = Kematian::latest('tarikh_meninggal')->limit(5)->get();
        
        // Get recent PPJUB activities for Perkhidmatan Terkini tab
        $recentPpjubActivities = Ppjub::latest()->limit(5)->get();
        
        // Get monthly death trends for the last 12 months based on tarikh_meninggal
        $monthlyDeaths = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Kematian::whereYear('tarikh_meninggal', $month->year)
                             ->whereMonth('tarikh_meninggal', $month->month)
                             ->count();
            $monthlyDeaths[] = [
                'month' => $month->format('M'),
                'count' => $count
            ];
        }
        
        // Get PPJUB status distribution
        $ppjubStatus = Ppjub::selectRaw('status, count(*) as count')
                            ->groupBy('status')
                            ->get()
                            ->pluck('count', 'status')
                            ->toArray();
        
        // Calculate service status (based on recent activities)
        $recentActivity = Kematian::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $serviceStatus = $recentActivity > 0 ? 95 : 85; // 95% if recent activity, 85% if not
        
        // Get new waris this month based on tarikh_meninggal
        $newWarisThisMonth = Kematian::whereMonth('tarikh_meninggal', Carbon::now()->month)->count();
        
        return view('overview', compact(
            'user',
            'totalPpjub',
            'totalKematian', 
            'totalWaris',
            'ppjubGrowth',
            'kematianGrowth',
            'recentPpjub',
            'recentKematian',
            'recentDeaths',
            'recentPpjubActivities',
            'monthlyDeaths',
            'ppjubStatus',
            'serviceStatus',
            'newWarisThisMonth'
        ));
    }
}
