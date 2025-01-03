<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PatientController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.patient', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->only([
            'name',
            'email',
            'phone',
            'gender',
            'date_of_birth',
            'role',
            'address',
        ]));

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'role' => 'required|in:doctor,patient',
            'password' => 'required|string|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        ]);

        // Thêm user vào database
        User::create([
            'FullName' => $validatedData['fullname'],
            'username' => $validatedData['username'],
            'Email' => $validatedData['email'],
            'PhoneNumber' => $validatedData['phone'],
            'RoleID' => $validatedData['role'], // RoleID có thể được ánh xạ
            'password' => bcrypt($validatedData['password']),
        ]);

        return response()->json(['message' => 'User added successfully!']);
    }

}
