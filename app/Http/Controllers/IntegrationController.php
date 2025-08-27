<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use App\Models\WeatherConfiguration;
use App\Models\EmailConfiguration;
use App\Models\ApiConfiguration;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Facades\Activity;

class IntegrationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $status = $request->get('status');
        $jenis = $request->get('jenis');
        
        $query = Integration::with(['createdBy', 'updatedBy']);
        
        if ($search) {
            $query->search($search);
        }
        
        if ($status) {
            $query->filterByStatus($status);
        }
        
        if ($jenis) {
            $query->filterByType($jenis);
        }
        
        $integrations = $query->orderBy('created_at', 'desc')->paginate(15);
        $statuses = Integration::distinct()->pluck('status')->filter()->sort()->values();
        $types = Integration::distinct()->pluck('jenis')->filter()->sort()->values();
        
        // Get weather configuration
        $weatherConfig = WeatherConfiguration::first();
        if (!$weatherConfig) {
            // Create default configuration if none exists
            $weatherConfig = WeatherConfiguration::create([
                'provider' => 'OpenWeatherMap',
                'api_key' => '',
                'base_url' => 'https://api.openweathermap.org/data/2.5',
                'default_location' => 'Kuala Lumpur, MY',
                'latitude' => 3.1390,
                'longitude' => 101.6869,
                'units' => 'metric',
                'language' => 'ms',
                'update_frequency' => 30,
                'cache_duration' => 15,
                'is_active' => true
            ]);
        }
        
        // Get email configuration
        $emailConfig = EmailConfiguration::first();
        if (!$emailConfig) {
            // Create default configuration if none exists
            $emailConfig = EmailConfiguration::create([
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 587,
                'username' => 'noreply@ekubur.com',
                'password' => '',
                'encryption' => 'TLS',
                'authentication' => 'Required',
                'from_name' => 'E-Kubur System',
                'reply_to' => 'support@ekubur.com',
                'connection_timeout' => 30,
                'max_retries' => 3,
                'is_active' => true
            ]);
        }
        
        // Get API configuration
        $apiConfig = ApiConfiguration::first();
        if (!$apiConfig) {
            // Create default configuration if none exists
            $apiConfig = ApiConfiguration::create([
                'base_url' => 'https://api.ekubur.com/v1',
                'version' => 'v1',
                'auth_type' => 'Bearer Token',
                'api_key' => null,
                'secret_key' => null,
                'access_token' => null,
                'rate_limit' => '1000 requests/hour',
                'timeout' => '30 saat',
                'max_retries' => '3',
                'ssl_verification' => 'Enabled',
                'logging_level' => 'Info',
            ]);
        }
        // Fetch latest Sanctum token name for current user (if any)
        $latestToken = null;
        if ($user) {
            $latestToken = PersonalAccessToken::where('tokenable_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        
        return view('integrations.index', compact('integrations', 'statuses', 'types', 'user', 'weatherConfig', 'emailConfig', 'apiConfig', 'latestToken'));
    }

    public function create()
    {
        $user = Auth::user();
        return view('integrations.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif,Dalam Pembangunan',
            'penerangan' => 'nullable|string',
            'url_endpoint' => 'nullable|url|max:255',
            'api_key' => 'nullable|string|max:255',
        ]);

        $integration = Integration::create([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'status' => $request->status,
            'penerangan' => $request->penerangan,
            'url_endpoint' => $request->url_endpoint,
            'api_key' => $request->api_key,
            'created_by' => Auth::id(),
        ]);

        activity('integrations')
            ->event('created')
            ->causedBy($user)
            ->performedOn($integration)
            ->withProperties([
                'nama' => $integration->nama,
                'jenis' => $integration->jenis,
                'status' => $integration->status,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Integrasi dicipta');

        return redirect()->route('integrations.index')
            ->with('success', 'Integrasi berjaya dicipta.');
    }

    public function show(Integration $integration)
    {
        $user = Auth::user();
        return view('integrations.show', compact('integration', 'user'));
    }

    public function edit(Integration $integration)
    {
        $user = Auth::user();
        return view('integrations.edit', compact('integration', 'user'));
    }

    public function update(Request $request, Integration $integration)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif,Dalam Pembangunan',
            'penerangan' => 'nullable|string',
            'url_endpoint' => 'nullable|url|max:255',
            'api_key' => 'nullable|string|max:255',
        ]);

        $integration->update([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'status' => $request->status,
            'penerangan' => $request->penerangan,
            'url_endpoint' => $request->url_endpoint,
            'api_key' => $request->api_key,
            'updated_by' => Auth::id(),
        ]);

        activity('integrations')
            ->event('updated')
            ->causedBy($user)
            ->performedOn($integration)
            ->withProperties([
                'nama' => $integration->nama,
                'jenis' => $integration->jenis,
                'status' => $integration->status,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Integrasi dikemaskini');

        return redirect()->route('integrations.index')
            ->with('success', 'Integrasi berjaya dikemaskini.');
    }

    public function destroy(Integration $integration)
    {
        $integration->delete();
        activity('integrations')
            ->event('deleted')
            ->causedBy($user)
            ->performedOn($integration)
            ->withProperties([
                'nama' => $integration->nama,
                'jenis' => $integration->jenis,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Integrasi dipadam');
        
        return redirect()->route('integrations.index')
            ->with('success', 'Integrasi berjaya dipadamkan.');
    }

    public function export()
    {
        $integrations = Integration::with(['createdBy', 'updatedBy'])->get();
        
        $filename = 'integrations_' . date('Y-m-d_H-i-s') . '.csv';
        $filepath = storage_path('app/public/' . $filename);
        
        $file = fopen($filepath, 'w');
        
        // CSV Headers
        fputcsv($file, [
            'ID', 'Nama', 'Jenis', 'Status', 'Penerangan', 'URL Endpoint', 
            'API Key', 'Terakhir Sync', 'Dicipta Oleh', 'Dikemaskini Oleh', 
            'Dicipta Pada', 'Dikemaskini Pada'
        ]);
        
        // CSV Data
        foreach ($integrations as $integration) {
            fputcsv($file, [
                $integration->id,
                $integration->nama,
                $integration->jenis,
                $integration->status,
                $integration->penerangan,
                $integration->url_endpoint,
                $integration->api_key,
                $integration->terakhir_sync_formatted,
                $integration->createdBy->name ?? '-',
                $integration->updatedBy->name ?? '-',
                $integration->created_at_formatted,
                $integration->updated_at_formatted,
            ]);
        }
        
        fclose($file);
        
        return response()->download($filepath)->deleteFileAfterSend();
    }
}
