<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientWardAllocation;
use App\Models\Ward;
use App\Models\WardBed;
use App\Models\WardBedHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientWardAllocationController extends Controller
{
    /**
     * Display a listing of the allocations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'active');
        $wardId = $request->input('ward_id');
        $search = $request->input('search');

        $query = PatientWardAllocation::with(['patient', 'wardBed.ward', 'allocatedBy']);

        if ($status === 'active') {
            $query->whereNull('DischargeDate');
        } elseif ($status === 'discharged') {
            $query->whereNotNull('DischargeDate');
        }

        if ($wardId) {
            $query->whereHas('wardBed', function ($q) use ($wardId) {
                $q->where('WardID', $wardId);
            });
        }

        if ($search) {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('FullName', 'like', "%{$search}%");
            })->orWhereHas('wardBed', function ($q) use ($search) {
                $q->where('BedNumber', 'like', "%{$search}%");
            });
        }

        $allocations = $query->orderBy('AllocationDate', 'desc')
                           ->paginate(15);

        $wards = Ward::orderBy('WardName')->get();

        return view('allocations.index', compact('allocations', 'wards', 'status', 'wardId', 'search'));
    }

    /**
     * Show the form for creating a new allocation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get users with patient role (RoleID) without active bed allocation
        $patients = \App\Models\User::where('RoleID', 'patient')
                          ->whereDoesntHave('bedAllocations', function($query) {
                              $query->whereNull('DischargeDate');
                          })
                          ->get()
                          ->map(function($user) {
                              return [
                                  'PatientID' => $user->UserID,
                                  'FullName' => $user->FullName,
                              ];
                          });
        
        $wards = Ward::orderBy('WardName')->get();
        
        return view('allocations.create', compact('patients', 'wards'));
    }

    /**
     * Store a newly created allocation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'PatientID' => 'required|exists:users,UserID',
            'WardBedID' => 'required|exists:ward_beds,WardBedID',
            'AllocationDate' => 'required|date',
            'Notes' => 'nullable|string|max:255',
        ]);

        $bed = WardBed::findOrFail($request->WardBedID);

        if ($bed->Status !== 'available') {
            return redirect()->back()
                ->with('error', 'Selected bed is not available')
                ->withInput();
        }

        // Check if patient already has an active allocation
        $hasActive = PatientWardAllocation::where('PatientID', $request->PatientID)
                                        ->whereNull('DischargeDate')
                                        ->exists();

        if ($hasActive) {
            return redirect()->back()
                ->with('error', 'Patient already has an active bed allocation')
                ->withInput();
        }

        DB::transaction(function () use ($request, $bed) {
            // Create allocation
            $allocation = PatientWardAllocation::create([
                'PatientID' => $request->PatientID,
                'WardBedID' => $request->WardBedID,
                'AllocationDate' => $request->AllocationDate,
                'Notes' => $request->Notes,
                'AllocatedByUserID' => Auth::user()->UserID,
            ]);

            // Update bed status
            $bed->update(['Status' => 'occupied']);

            // Close previous history record
            WardBedHistory::where('WardBedID', $bed->WardBedID)
                ->whereNull('ToDate')
                ->update(['ToDate' => now()]);

            // Create new history record
            WardBedHistory::create([
                'WardBedID' => $bed->WardBedID,
                'PatientID' => $request->PatientID,
                'FromDate' => now(),
                'ToDate' => null,
                'Status' => 'occupied',
                'Note' => 'Assigned to patient',
                'UpdatedByUserID' => Auth::user()->UserID,
            ]);
        });

        return redirect()->route('allocations.index')
            ->with('success', 'Patient assigned to bed successfully');
    }

    /**
     * Display the specified allocation.
     *
     * @param  \App\Models\PatientWardAllocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function show(PatientWardAllocation $allocation)
    {
        $allocation->load('patient', 'wardBed.ward', 'allocatedBy');
        return view('allocations.show', compact('allocation'));
    }

    /**
     * Show the form for editing the specified allocation.
     * Only notes can be edited for existing allocations.
     *
     * @param  \App\Models\PatientWardAllocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function edit(PatientWardAllocation $allocation)
    {
        return view('allocations.edit', compact('allocation'));
    }

    /**
     * Update the specified allocation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PatientWardAllocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PatientWardAllocation $allocation)
    {
        $request->validate([
            'Notes' => 'nullable|string|max:255',
        ]);

        $allocation->update([
            'Notes' => $request->Notes,
        ]);

        return redirect()->route('allocations.show', $allocation)
            ->with('success', 'Allocation notes updated successfully');
    }

    /**
     * Process a patient discharge from a bed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PatientWardAllocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function discharge(Request $request, PatientWardAllocation $allocation)
    {
        $request->validate([
            'DischargeDate' => 'required|date',
            'DischargeNotes' => 'nullable|string|max:255',
            'BedStatus' => 'required|in:available,maintenance',
        ]);

        if ($allocation->DischargeDate) {
            return redirect()->back()
                ->with('error', 'This allocation has already been discharged');
        }

        $bed = $allocation->wardBed;

        DB::transaction(function () use ($request, $allocation, $bed) {
            // Update allocation
            $allocation->update([
                'DischargeDate' => $request->DischargeDate,
                'Notes' => $allocation->Notes . "\n\nDischarge Notes: " . ($request->DischargeNotes ?? 'N/A'),
            ]);

            // Update bed status
            $bed->update(['Status' => $request->BedStatus]);

            // Close previous history record
            WardBedHistory::where('WardBedID', $bed->WardBedID)
                ->whereNull('ToDate')
                ->update(['ToDate' => now()]);

            // Create new history record
            WardBedHistory::create([
                'WardBedID' => $bed->WardBedID,
                'PatientID' => null,
                'FromDate' => now(),
                'ToDate' => null,
                'Status' => $request->BedStatus,
                'Note' => 'Patient discharged, bed status set to ' . $request->BedStatus,
                'UpdatedByUserID' => Auth::user()->UserID,
            ]);
        });

        return redirect()->route('allocations.index')
            ->with('success', 'Patient discharged successfully');
    }

    /**
     * Get patients without active allocations (AJAX)
     */
    public function getAvailablePatients()
    {
        $patients = \App\Models\User::where('RoleID', 'patient')
                          ->whereDoesntHave('bedAllocations', function($query) {
                              $query->whereNull('DischargeDate');
                          })
                          ->get()
                          ->map(function($user) {
                              return [
                                  'PatientID' => $user->UserID,
                                  'FullName' => $user->FullName,
                                  'Gender' => $user->Gender ?? 'Unknown',
                                  'DateOfBirth' => $user->DateOfBirth ?? 'Unknown',
                              ];
                          });
        
        return response()->json($patients);
    }
} 