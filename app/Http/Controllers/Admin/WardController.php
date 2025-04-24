<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ward;
use App\Models\WardType;
use App\Models\Doctor;

class WardController extends Controller
{
    /**
     * Display ward management page
     */
    public function index()
    {
        $wardTypes = WardType::all();
        $wards = Ward::with(['wardType', 'doctor.user'])->get()->map(function($ward) {
            // Add doctor name from the user model
            if ($ward->doctor && $ward->doctor->user) {
                $ward->DoctorName = $ward->doctor->user->FullName;
            } else {
                $ward->DoctorName = 'N/A';
            }
            
            // Add ward type name
            if ($ward->wardType) {
                $ward->TypeName = $ward->wardType->TypeName;
            } else {
                $ward->TypeName = 'N/A';
            }
            
            return $ward;
        });
        
        // Get doctors with their names from the users table
        $doctors = Doctor::with('user')->get()->map(function($doctor) {
            return [
                'DoctorID' => $doctor->DoctorID,
                'FullName' => $doctor->user ? $doctor->user->FullName : 'Unknown', 
                'Speciality' => $doctor->Speciality,
                'Title' => $doctor->Title
            ];
        });

        return view('admin.ward', compact('wardTypes', 'wards', 'doctors'));
    }

    /**
     * Store a newly created ward
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'ward_name' => 'required|string|max:100',
            'ward_type' => 'required|integer',
            'capacity' => 'required|integer|min:1',
            'doctor_id' => 'required|integer'
        ]);

        try {
            $ward = new Ward();
            $ward->WardName = $validated['ward_name'];
            $ward->WardTypeID = $validated['ward_type'];
            $ward->Capacity = $validated['capacity'];
            $ward->DoctorID = $validated['doctor_id'];
            $ward->CurrentOccupancy = 0;
            $ward->save();

            return response()->json([
                'success' => true,
                'message' => 'Ward created successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating ward: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified ward
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $validated = $request->validate([
            'ward_name' => 'required|string|max:100',
            'ward_type' => 'required|integer',
            'capacity' => 'required|integer|min:1',
            'doctor_id' => 'required|integer'
        ]);

        try {
            $ward = Ward::findOrFail($id);
            $ward->WardName = $validated['ward_name'];
            $ward->WardTypeID = $validated['ward_type'];
            $ward->Capacity = $validated['capacity'];
            $ward->DoctorID = $validated['doctor_id'];
            $ward->save();

            return response()->json([
                'success' => true,
                'message' => 'Ward updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating ward: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified ward
     */
    public function destroy($id)
    {
        try {
            $ward = Ward::findOrFail($id);
            $ward->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ward deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting ward: ' . $e->getMessage()
            ], 500);
        }
    }
}
