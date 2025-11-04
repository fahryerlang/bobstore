<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,kasir,customer,pembeli'],
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ];

        // Jika role adalah customer (member), inisialisasi member fields
        if ($validated['role'] === 'customer') {
            $userData['points'] = 0;
            $userData['member_level'] = 'bronze';
            $userData['member_since'] = now();
        }

        User::create($userData);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validasi dasar
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:admin,kasir,customer,pembeli'],
        ];

        // Tambahkan validasi password hanya jika field password diisi
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate($rules);

        $oldRole = $user->role;
        $newRole = $validated['role'];

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $newRole;

        // Jika mengubah dari non-member menjadi customer (member)
        if ($oldRole !== 'customer' && $newRole === 'customer') {
            // Inisialisasi member fields
            $user->points = 0;
            $user->member_level = 'bronze';
            $user->member_since = now();
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $message = 'Data pengguna berhasil diperbarui!';
        
        // Tambahkan notifikasi khusus jika dikonversi jadi member
        if ($oldRole !== 'customer' && $newRole === 'customer') {
            $message = 'Pengguna berhasil dikonversi menjadi Member dengan status Bronze! Poin loyalty telah diaktifkan.';
        } elseif ($oldRole === 'customer' && $newRole !== 'customer') {
            $message = 'Role pengguna berhasil diubah. Poin loyalty tidak akan bisa digunakan lagi.';
        }

        return redirect()->route('users.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
