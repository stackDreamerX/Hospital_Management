<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{

  

    public function staff()
    {
        
        $doctors = Doctor::with('user')->get();
        return view('admin.staff', compact('doctors'));
    }

    public function createDoctor(Request $request)
    {    
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'fullname' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,Email',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password',
            'speciality' => 'required|string|max:100',
            'title' => 'required|string|max:100',
        ]);

        // Start a database transaction to ensure both user and doctor are created
        DB::beginTransaction();

        try {
            // Create the user first
            $user = User::create([
                'username' => $request->username,
                'FullName' => $request->fullname,
                'Email' => $request->email,
                'PhoneNumber' => $request->phone,
                'password' => bcrypt($request->password),
                'RoleID' => 'doctor',
            ]);

            // Then create the doctor record
            Doctor::create([
                'UserID' => $user->UserID,
                'Speciality' => $request->speciality,
                'Title' => $request->title,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Doctor created successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating doctor: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create doctor: ' . $e->getMessage()]);
        }
    }


    public function editDoctor(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->update([
            'Speciality' => $request->speciality,
            'Title' => $request->title,
        ]);

        $user = $doctor->user;
        $user->update([
            'FullName' => $request->fullname,
            'username' => $request->username,
            'Email' => $request->email,
            'PhoneNumber' => $request->phone,
        ]);

        return response()->json(['message' => 'Doctor updated successfully.']);
    }
    
    public function deleteDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete(); 

        $user = User::findOrFail($doctor->UserID);
        $user->update(['RoleID' => 'patient']);

        return response()->json(['message' => 'Doctor deleted successfully.']);
    }

//API
    public function getDoctorsList()
    {
        $doctors = Doctor::with('user')->get();
        return response()->json($doctors);
    }


}
