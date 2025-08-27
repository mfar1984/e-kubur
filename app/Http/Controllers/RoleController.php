<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Facades\Activity;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $search = $request->get('search');
        $query = Role::query();
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        $roles = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('roles.index', compact('roles', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $permissions = Permission::all();
        
        return view('roles.create', compact('permissions', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);
        // Log create
        activity('roles')
            ->event('created')
            ->causedBy(Auth::user())
            ->performedOn($role)
            ->withProperties([
                'name' => $role->name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Kumpulan akses dicipta');
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Kumpulan akses berjaya dicipta.');
    }

    public function show(Role $role)
    {
        $user = Auth::user();
        $permissions = $role->permissions;
        $users = $role->users;
        
        return view('roles.show', compact('role', 'permissions', 'users', 'user'));
    }

    public function edit(Role $role)
    {
        $user = Auth::user();
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions', 'user'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);
        // Log update
        activity('roles')
            ->event('updated')
            ->causedBy(Auth::user())
            ->performedOn($role)
            ->withProperties([
                'name' => $role->name,
                'permissions' => $request->permissions ?? [],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Kumpulan akses dikemaskini');
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Kumpulan akses berjaya dikemaskini.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Kumpulan akses tidak boleh dipadam kerana masih ada pengguna yang menggunakannya.');
        }

        $role->delete();
        // Log delete
        activity('roles')
            ->event('deleted')
            ->causedBy(Auth::user())
            ->performedOn($role)
            ->withProperties([
                'name' => $role->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Kumpulan akses dipadam');

        return redirect()->route('roles.index')
            ->with('success', 'Kumpulan akses berjaya dipadam.');
    }

    public function export()
    {
        $roles = Role::with('permissions')->get();
        
        $filename = 'kumpulan-akses-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($roles) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Nama Kumpulan', 'Izin', 'Bilangan Pengguna', 'Tarikh Cipta']);
            
            // Data
            foreach ($roles as $role) {
                $permissions = $role->permissions->pluck('name')->implode(', ');
                $userCount = $role->users()->count();
                
                fputcsv($file, [
                    $role->name,
                    $permissions,
                    $userCount,
                    $role->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
