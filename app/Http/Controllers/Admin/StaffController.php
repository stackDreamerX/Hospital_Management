<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            'username' => 'required|string',
            'speciality' => 'required|string|max:100',
            'title' => 'required|string|max:100',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Username not exist!']);
        }

        if ($user->RoleID === 'doctor') {
            return response()->json(['success' => false, 'message' => 'This user is already a doctor!']);
        }

        $user->RoleID = 'doctor';
        $user->save();

        Doctor::create([
            'UserID' => $user->UserID,
            'Speciality' => $request->speciality,
            'Title' => $request->title,
        ]);

        return response()->json(['success' => true, 'message' => 'Doctor created successfully!']);
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
