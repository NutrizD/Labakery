<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // ← tambah ini

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password'       => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'           => ['required', 'string', Rule::in(['admin', 'kasir', 'super_admin'])],
            'profile_photo'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // ← validasi foto
        ]);

        $userData = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // === proses upload foto (opsional) ===
        if ($request->hasFile('profile_photo')) {
            // hapus lama jika ada
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            // simpan baru ke storage/app/public/avatars
            $path = $request->file('profile_photo')->store('avatars', 'public');
            $userData['profile_photo'] = $path; // simpan path relatif (ex: avatars/abc.jpg)
        }

        $user->update($userData);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
