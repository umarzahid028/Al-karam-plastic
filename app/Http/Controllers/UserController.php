<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Show all users
    public function index()
    {
        $users = User::paginate(5);
        // Purchase::paginate(5);
        return view('users.index', compact('users'));
    }

    // Show create form
    public function create()
    {
        return view('users.create');
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'contact_no' => 'nullable|string|max:20',
            'salary'     => 'nullable|numeric',
            'role'       => 'required|string',
            'status'     => 'required|string'
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'contact_no' => $request->contact_no,
            'salary'     => $request->salary,
            'role'       => $request->role,
            'status'     => $request->status,
        ]);

        return redirect()->route('users.index')->with('success', 'User added successfully!');
    }

    // Show single user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    // Update status via AJAX
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => true, 'status' => $user->status]);
    }
}
