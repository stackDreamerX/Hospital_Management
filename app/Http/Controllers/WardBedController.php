<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\WardBed;
use App\Models\WardBedHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WardBedController extends Controller
{
    /**
     * Display a listing of the beds.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $wardId = $request->input('ward_id');

        $query = WardBed::with('ward');

        if ($status) {
            $query->where('Status', $status);
        }

        if ($wardId) {
            $query->where('WardID', $wardId);
        }

        $beds = $query->orderBy('WardID')
                      ->orderBy('BedNumber')
                      ->paginate(15);

        $wards = Ward::orderBy('WardName')->get();

        return view('beds.index', compact('beds', 'wards', 'status', 'wardId'));
    }

    /**
     * Show the form for creating a new bed.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $wards = Ward::orderBy('WardName')->get();
        return view('beds.create', compact('wards'));
    }

    /**
     * Store a newly created bed in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'WardID' => 'required|exists:wards,WardID',
            'BedNumber' => [
                'required',
                'string',
                'max:10',
                Rule::unique('ward_beds')->where(function ($query) use ($request) {
                    return $query->where('WardID', $request->WardID);
                }),
            ],
            'Status' => 'required|in:available,occupied,maintenance',
        ]);

        DB::transaction(function () use ($request) {
            $bed = WardBed::create([
                'WardID' => $request->WardID,
                'BedNumber' => $request->BedNumber,
                'Status' => $request->Status,
            ]);

            // Create history record
            WardBedHistory::create([
                'WardBedID' => $bed->WardBedID,
                'PatientID' => null,
                'FromDate' => now(),
                'ToDate' => null,
                'Status' => $bed->Status,
                'Note' => 'Bed created',
                'UpdatedByUserID' => Auth::id(),
            ]);
        });

        return redirect()->route('beds.index')
            ->with('success', 'Bed created successfully');
    }

    /**
     * Display the specified bed.
     *
     * @param  \App\Models\WardBed  $bed
     * @return \Illuminate\Http\Response
     */
    public function show(WardBed $bed)
    {
        $bed->load('ward', 'currentAllocation.patient', 'history');
        return view('beds.show', compact('bed'));
    }

    /**
     * Show the form for editing the specified bed.
     *
     * @param  \App\Models\WardBed  $bed
     * @return \Illuminate\Http\Response
     */
    public function edit(WardBed $bed)
    {
        $wards = Ward::orderBy('WardName')->get();
        return view('beds.edit', compact('bed', 'wards'));
    }

    /**
     * Update the specified bed in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WardBed  $bed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WardBed $bed)
    {
        $request->validate([
            'WardID' => 'required|exists:wards,WardID',
            'BedNumber' => [
                'required',
                'string',
                'max:10',
                Rule::unique('ward_beds')->where(function ($query) use ($request) {
                    return $query->where('WardID', $request->WardID);
                })->ignore($bed->WardBedID, 'WardBedID'),
            ],
            'Status' => 'required|in:available,occupied,maintenance',
        ]);

        $oldStatus = $bed->Status;
        $newStatus = $request->Status;
        
        DB::transaction(function () use ($request, $bed, $oldStatus, $newStatus) {
            // Close previous history record if status changed
            if ($oldStatus != $newStatus) {
                WardBedHistory::where('WardBedID', $bed->WardBedID)
                    ->whereNull('ToDate')
                    ->update(['ToDate' => now()]);

                // Create new history record
                WardBedHistory::create([
                    'WardBedID' => $bed->WardBedID,
                    'PatientID' => null,
                    'FromDate' => now(),
                    'ToDate' => null,
                    'Status' => $newStatus,
                    'Note' => 'Status changed from ' . $oldStatus . ' to ' . $newStatus,
                    'UpdatedByUserID' => Auth::id(),
                ]);
            }

            $bed->update([
                'WardID' => $request->WardID,
                'BedNumber' => $request->BedNumber,
                'Status' => $request->Status,
            ]);
        });

        return redirect()->route('beds.index')
            ->with('success', 'Bed updated successfully');
    }

    /**
     * Remove the specified bed from storage.
     *
     * @param  \App\Models\WardBed  $bed
     * @return \Illuminate\Http\Response
     */
    public function destroy(WardBed $bed)
    {
        if ($bed->Status === 'occupied') {
            return redirect()->route('beds.index')
                ->with('error', 'Cannot delete an occupied bed');
        }

        $bed->delete();

        return redirect()->route('beds.index')
            ->with('success', 'Bed deleted successfully');
    }

    /**
     * Change the status of a bed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WardBed  $bed
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, WardBed $bed)
    {
        $request->validate([
            'Status' => 'required|in:available,occupied,maintenance',
            'Note' => 'nullable|string|max:255',
        ]);

        $oldStatus = $bed->Status;
        $newStatus = $request->Status;

        if ($oldStatus === 'occupied' && $newStatus !== 'occupied') {
            return redirect()->route('beds.show', $bed)
                ->with('error', 'Occupied beds must be discharged through the patient allocation screen');
        }
        
        DB::transaction(function () use ($request, $bed, $oldStatus, $newStatus) {
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
                'Status' => $newStatus,
                'Note' => $request->Note ?? 'Status changed from ' . $oldStatus . ' to ' . $newStatus,
                'UpdatedByUserID' => Auth::id(),
            ]);

            $bed->update([
                'Status' => $newStatus
            ]);
        });

        return redirect()->route('beds.show', $bed)
            ->with('success', 'Bed status updated successfully');
    }

    /**
     * Get available beds for a specific ward (or all wards)
     * Used for AJAX requests
     */
    public function getAvailableBeds(Request $request)
    {
        $wardId = $request->input('ward_id');
        
        $query = WardBed::where('Status', 'available');
        
        if ($wardId) {
            $query->where('WardID', $wardId);
        }
        
        $beds = $query->orderBy('WardID')
                     ->orderBy('BedNumber')
                     ->with('ward')
                     ->get();
        
        return response()->json($beds);
    }
} 