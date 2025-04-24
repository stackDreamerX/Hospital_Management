<?php

namespace App\Http\Controllers;

use App\Models\WardBed;
use App\Models\WardBedHistory;
use Illuminate\Http\Request;

class WardBedHistoryController extends Controller
{
    /**
     * Display a listing of the bed history records.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bedId = $request->input('bed_id');
        $wardId = $request->input('ward_id');
        $patientId = $request->input('patient_id');
        $status = $request->input('status');

        $query = WardBedHistory::with(['wardBed.ward', 'patient', 'updatedBy']);

        if ($bedId) {
            $query->where('WardBedID', $bedId);
        }

        if ($wardId) {
            $query->whereHas('wardBed', function ($q) use ($wardId) {
                $q->where('WardID', $wardId);
            });
        }

        if ($patientId) {
            $query->where('PatientID', $patientId);
        }

        if ($status) {
            $query->where('Status', $status);
        }

        $history = $query->orderBy('FromDate', 'desc')
                       ->paginate(20);

        return view('bed-history.index', compact('history', 'bedId', 'wardId', 'patientId', 'status'));
    }

    /**
     * Display the specified history record.
     *
     * @param  \App\Models\WardBedHistory  $history
     * @return \Illuminate\Http\Response
     */
    public function show(WardBedHistory $history)
    {
        $history->load('wardBed.ward', 'patient', 'updatedBy');
        return view('bed-history.show', compact('history'));
    }

    /**
     * Display history for a specific bed.
     *
     * @param  \App\Models\WardBed  $bed
     * @return \Illuminate\Http\Response
     */
    public function forBed(WardBed $bed)
    {
        $history = WardBedHistory::where('WardBedID', $bed->WardBedID)
                               ->with(['patient', 'updatedBy'])
                               ->orderBy('FromDate', 'desc')
                               ->paginate(20);

        return view('bed-history.for-bed', compact('bed', 'history'));
    }

    /**
     * Generate a report of bed utilization
     *
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        $fromDate = $request->input('from_date', now()->subDays(30)->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));
        $wardId = $request->input('ward_id');

        // Bed usage statistics
        $query = WardBedHistory::whereDate('FromDate', '>=', $fromDate)
                              ->whereDate('FromDate', '<=', $toDate);

        if ($wardId) {
            $query->whereHas('wardBed', function ($q) use ($wardId) {
                $q->where('WardID', $wardId);
            });
        }

        $stats = $query->get();

        // Summarize by status
        $byStatus = $stats->groupBy('Status')->map->count();

        // Summarize by ward
        $byWard = $stats->groupBy(function ($item) {
            return $item->wardBed->ward->WardName ?? 'Unknown';
        })->map->count();

        // Calculate average occupation time for beds (in days)
        $occupiedRecords = $stats->where('Status', 'occupied');
        $avgOccupationTime = 0;
        
        if ($occupiedRecords->count() > 0) {
            $totalDays = $occupiedRecords->sum(function ($record) {
                $from = new \DateTime($record->FromDate);
                $to = $record->ToDate ? new \DateTime($record->ToDate) : now();
                $interval = $from->diff($to);
                return $interval->days ?: 1; // Minimum 1 day
            });
            
            $avgOccupationTime = $totalDays / $occupiedRecords->count();
        }

        // Get recent history for display
        $recentHistory = WardBedHistory::with(['wardBed.ward', 'patient', 'updatedBy'])
                                     ->whereDate('FromDate', '>=', $fromDate)
                                     ->whereDate('FromDate', '<=', $toDate)
                                     ->orderBy('FromDate', 'desc')
                                     ->paginate(15);

        return view('bed-history.report', compact(
            'recentHistory',
            'fromDate',
            'toDate',
            'wardId',
            'byStatus',
            'byWard',
            'avgOccupationTime'
        ));
    }
} 