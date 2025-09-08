<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6',
            'contact_no' => 'nullable|string',
            'salary'     => 'nullable|numeric',
            'role'       => 'required|in:admin,manager,user', // 👈 only 3 roles
        ]);
    
        $user = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'contact_no' => $validated['contact_no'] ?? null,
            'salary'     => $validated['salary'] ?? null,
            'role'       => $validated['role'],
        ]);
    
        return response()->json($user, 201);
    }
    
    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'name'       => $request->name ?? $user->name,
            'email'      => $request->email ?? $user->email,
            'password'   => $request->password ? Hash::make($request->password) : $user->password,
            'contact_no' => $request->contact_no ?? $user->contact_no,
            'salary'     => $request->salary ?? $user->salary,
            'role'       => $request->role ?? $user->role,
        ]);

        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(null, 204);
    }
}
