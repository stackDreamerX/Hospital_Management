<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware is applied in routes
    }

    /**
     * Display a listing of the feedback for admins.
     */
    public function index(Request $request)
    {
        $query = Feedback::with('user')->latest();
        
        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('doctor_name', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('is_highlighted')) {
            $query->where('is_highlighted', true);
        }
        
        $feedback = $query->paginate(10);
        
        return view('admin.feedback.index', compact('feedback'));
    }

    /**
     * Display the admin dashboard for feedback.
     */
    public function adminDashboard()
    {
        $pendingCount = Feedback::pending()->count();
        $approvedCount = Feedback::approved()->count();
        $rejectedCount = Feedback::rejected()->count();
        $highlightedCount = Feedback::highlighted()->count();
        $totalCount = Feedback::count();
        
        $latestFeedback = Feedback::with('user')
            ->latest()
            ->take(5)
            ->get();
            
        $pendingFeedback = Feedback::with('user')
            ->pending()
            ->latest()
            ->paginate(10);
            
        return view('admin.feedback.dashboard', compact(
            'pendingCount', 
            'approvedCount', 
            'rejectedCount', 
            'highlightedCount', 
            'totalCount', 
            'latestFeedback',
            'pendingFeedback'
        ));
    }

    /**
     * Display a listing of public feedback.
     */
    public function publicFeedback()
    {
        $highlightedFeedback = Feedback::with('user')
            ->approved()
            ->highlighted()
            ->latest()
            ->take(3)
            ->get();
            
        $regularFeedback = Feedback::with('user')           
            ->latest()
            ->paginate(6);
            
        return view('feedback.public', compact('highlightedFeedback', 'regularFeedback'));
    }

    /**
     * Display a listing of the current user's feedback submissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function userFeedback()
    {
        $feedbacks = Feedback::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('feedback.user-index', compact('feedbacks'));
    }

    /**
     * Show the form for creating a new feedback.
     */
    public function create()
    {
        return view('feedback.create');
    }

    /**
     * Store a newly created feedback in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|min:10',
            'category' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:100',
            'doctor_name' => 'nullable|string|max:100',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $feedback = new Feedback();
        $feedback->user_id = Auth::id();
        $feedback->subject = $request->subject;
        $feedback->rating = $request->rating;
        $feedback->message = $request->message;
        $feedback->category = $request->category;
        $feedback->department = $request->department;
        $feedback->doctor_name = $request->doctor_name;
        $feedback->is_anonymous = $request->has('is_anonymous');
        $feedback->status = 'pending';
        $feedback->save();

        return redirect()->route('feedback.thank-you');
    }

    /**
     * Display the specified feedback.
     */
    public function show(Feedback $feedback)
    {
        return view('admin.feedback.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified feedback.
     */
    public function edit(Feedback $feedback)
    {
        return view('admin.feedback.edit', compact('feedback'));
    }

    /**
     * Update the specified feedback in storage.
     */
    public function update(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'category' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:100',
            'doctor_name' => 'nullable|string|max:100',
            'status' => 'required|in:pending,approved,rejected',
            'is_anonymous' => 'nullable|boolean',
            'is_highlighted' => 'nullable|boolean',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Handle checkbox inputs
        $validated['is_highlighted'] = $request->has('is_highlighted');
        $validated['is_anonymous'] = $request->has('is_anonymous');
        
        // Set the reviewed timestamp if status is changed
        if ($feedback->status !== $validated['status']) {
            $validated['admin_reviewed_at'] = now();
        }

        $feedback->update($validated);

        return redirect()->route('admin.feedback')
            ->with('success', 'Feedback updated successfully.');
    }

    /**
     * Update the status of the specified feedback.
     */
    public function updateStatus(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $feedback->status = $validated['status'];
        $feedback->admin_notes = $validated['admin_notes'] ?? $feedback->admin_notes;
        $feedback->admin_reviewed_at = now();
        $feedback->save();

        return redirect()->back()
            ->with('success', 'Feedback status updated successfully.');
    }

    /**
     * Remove the specified feedback from storage.
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()->route('admin.feedback')
            ->with('success', 'Feedback deleted successfully.');
    }

    /**
     * Display thank you page after feedback submission.
     */
    public function thankYou()
    {
        return view('feedback.thank-you');
    }
} 