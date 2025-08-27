<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserAccessController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $search = $request->get('search');
        $role = $request->get('role');
        
        $query = User::with('roles');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($role) {
            $query->whereHas('roles', function($q) use ($role) {
                $q->where('name', $role);
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $roles = Role::all();
        
        return view('user-access.index', compact('users', 'roles', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $roles = Role::all();
        
        return view('user-access.create', compact('roles', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        return redirect()->route('user-access.index')
            ->with('success', 'Pengguna akses berjaya dicipta.');
    }

    public function show(User $userAccess)
    {
        $user = Auth::user();
        $roles = $userAccess->roles;
        $permissions = $userAccess->getAllPermissions();
        
        return view('user-access.show', compact('userAccess', 'roles', 'permissions', 'user'));
    }

    public function edit(User $userAccess)
    {
        $user = Auth::user();
        $roles = Role::all();
        $userRoles = $userAccess->roles->pluck('name')->toArray();
        
        return view('user-access.edit', compact('userAccess', 'roles', 'userRoles', 'user'));
    }

    public function update(Request $request, User $userAccess)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userAccess->id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
        ]);

        $userAccess->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $userAccess->update(['password' => Hash::make($request->password)]);
        }

        if ($request->has('roles')) {
            $userAccess->syncRoles($request->roles);
        } else {
            $userAccess->syncRoles([]);
        }

        return redirect()->route('user-access.index')
            ->with('success', 'Pengguna akses berjaya dikemaskini.');
    }

    public function destroy(User $userAccess)
    {
        if ($userAccess->id === Auth::id()) {
            return redirect()->route('user-access.index')
                ->with('error', 'Anda tidak boleh memadam akaun sendiri.');
        }

        $userAccess->delete();

        return redirect()->route('user-access.index')
            ->with('success', 'Pengguna akses berjaya dipadam.');
    }

    public function export()
    {
        $users = User::with('roles')->get();
        
        $filename = 'pengguna-akses-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Nama', 'Email', 'Telefon', 'Kumpulan Akses', 'Tarikh Cipta']);
            
            // Data
            foreach ($users as $user) {
                $roles = $user->roles->pluck('name')->implode(', ');
                
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $user->phone,
                    $roles ?: 'Tiada kumpulan',
                    $user->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
