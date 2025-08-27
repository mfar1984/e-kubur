<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\Facades\Activity as ActivityFacade;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $eventType = $request->get('event_type');
        $eventCategory = $request->get('event_category');
        $severity = $request->get('severity');
        $status = $request->get('status');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $query = Activity::with(['causer', 'subject']);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('event', 'like', "%{$search}%")
                  ->orWhere('log_name', 'like', "%{$search}%")
                  ->orWhere('causer_type', 'like', "%{$search}%");
            });
        }
        
        if ($eventType) {
            $query->where('event', $eventType);
        }
        
        if ($eventCategory) {
            $query->where('log_name', $eventCategory);
        }
        
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('created_at', '<=', $endDate . ' 23:59:59');
        }
        
        $activities = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get filter options
        $eventTypes = Activity::distinct()->pluck('event')->filter()->sort()->values();
        $eventCategories = Activity::distinct()->pluck('log_name')->filter()->sort()->values();
        
        return view('audit-logs.index', compact('activities', 'eventTypes', 'eventCategories', 'user'));
    }

    public function show(Activity $activity)
    {
        $user = Auth::user();
        return view('audit-logs.show', compact('activity', 'user'));
    }

    public function export(Request $request)
    {
        $query = Activity::with(['causer', 'subject']);
        
        // Apply filters
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', "%{$request->search}%")
                  ->orWhere('event', 'like', "%{$request->search}%")
                  ->orWhere('log_name', 'like', "%{$request->log_name}%");
            });
        }
        
        if ($request->event_type) {
            $query->where('event', $request->event_type);
        }
        
        if ($request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        }
        
        if ($request->end_date) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        
        $activities = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';
        $filepath = storage_path('app/public/' . $filename);
        
        $file = fopen($filepath, 'w');
        
        // CSV Headers
        fputcsv($file, [
            'ID', 'Event', 'Category', 'Description', 'User', 'Subject Type', 'Subject ID', 
            'Properties', 'IP Address', 'User Agent', 'Created At'
        ]);
        
        // CSV Data
        foreach ($activities as $activity) {
            fputcsv($file, [
                $activity->id,
                $activity->event,
                $activity->log_name,
                $activity->description,
                $activity->causer->name ?? 'System',
                $activity->subject_type ?? '-',
                $activity->subject_id ?? '-',
                json_encode($activity->properties),
                $activity->properties->get('ip_address', '-'),
                $activity->properties->get('user_agent', '-'),
                $activity->created_at->format('d/m/Y H:i:s'),
            ]);
        }
        
        fclose($file);
        
        return response()->download($filepath)->deleteFileAfterSend();
    }

    public function destroy(Activity $activity)
    {
        // Log meta before deleting
        $meta = [
            'activity_id' => $activity->id,
            'event' => $activity->event,
            'log_name' => $activity->log_name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        // Record an audit entry that a log will be deleted (before delete so we can reference it)
        activity('audit')
            ->event('audit_log_deleted')
            ->causedBy(Auth::user())
            ->performedOn($activity)
            ->withProperties($meta)
            ->log('Log audit dipadam');

        // Perform delete
        $activity->delete();
        
        return redirect()->route('audit-logs.index')
            ->with('success', 'Log audit berjaya dipadamkan.');
    }

    public function clearOldLogs(Request $request)
    {
        $days = $request->get('days', 90);
        $deleted = Activity::where('created_at', '<', now()->subDays($days))->delete();

        // Record an audit entry for bulk clear
        activity('audit')
            ->event('audit_logs_cleared')
            ->causedBy(Auth::user())
            ->withProperties([
                'days' => (int) $days,
                'deleted_count' => (int) $deleted,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Padam log audit lama');
        
        return redirect()->route('audit-logs.index')
            ->with('success', "Berjaya memadamkan {$deleted} log lama (lebih dari {$days} hari).");
    }
}
