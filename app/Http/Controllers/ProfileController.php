<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Facades\Activity;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pageTitle = 'Profil Pengguna - E-Kubur';
        
        return view('profile.index', compact('user', 'pageTitle'));
    }

    public function edit()
    {
        $user = Auth::user();
        $pageTitle = 'Edit Profil - E-Kubur';
        
        return view('profile.edit', compact('user', 'pageTitle'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ], [
            'name.required' => 'Nama adalah wajib diisi.',
            'email.required' => 'Emel adalah wajib diisi.',
            'email.email' => 'Format emel tidak sah.',
            'email.unique' => 'Emel ini sudah digunakan.',
            'phone.required' => 'Nombor telefon adalah wajib diisi.',
            'current_password.required_with' => 'Kata laluan semasa diperlukan untuk menukar kata laluan.',
            'new_password.min' => 'Kata laluan baru mesti sekurang-kurangnya 8 aksara.',
            'new_password.confirmed' => 'Pengesahan kata laluan tidak sepadan.',
        ]);

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Kata laluan semasa tidak betul.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        // Explicit activity log for profile update with IP/UA
        activity('user')
            ->event('updated')
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Profil pengguna dikemaskini');

        return redirect()->route('profile.index')->with('success', 'Profil berjaya dikemaskini.');
    }
}
