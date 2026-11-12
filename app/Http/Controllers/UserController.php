<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the user management page.
     */
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $status = trim($request->string('status')->toString());

        $users = User::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nestedQuery) use ($search): void {
                    $nestedQuery
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('role', 'like', '%'.$search.'%');
                });
            })
            ->when(in_array($status, ['aktif', 'nonaktif'], true), function ($query) use ($status): void {
                $query->where('aktif', $status === 'aktif');
            })
            ->orderByRaw("case when role = 'admin' then 0 else 1 end")
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('user.index', [
            'users' => $users,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('user.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'password' => ['required', 'confirmed', Password::defaults()],
            'aktif' => ['nullable', 'boolean'],
        ], [], [
            'name' => 'nama',
            'email' => 'email',
            'role' => 'role',
            'password' => 'password',
            'aktif' => 'status akun',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'aktif' => $request->boolean('aktif', true),
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('user.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('user.edit', [
            'managedUser' => $user,
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'aktif' => ['nullable', 'boolean'],
        ], [], [
            'name' => 'nama',
            'email' => 'email',
            'role' => 'role',
            'password' => 'password',
            'aktif' => 'status akun',
        ]);

        $newRole = $validated['role'];
        $newAktif = $request->boolean('aktif');

        if ($request->user()->is($user) && ($newRole !== 'admin' || ! $newAktif)) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Akun admin yang sedang dipakai tidak boleh diubah menjadi nonaktif atau diturunkan rolenya.');
        }

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $newRole,
            'aktif' => $newAktif,
        ];

        if ($request->filled('password')) {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);

        return redirect()
            ->route('user.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }
}
