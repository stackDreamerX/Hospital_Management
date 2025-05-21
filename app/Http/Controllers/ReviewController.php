<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Store a review from doctor profile page
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Check if this is a JSON request
            $isJsonRequest = $request->ajax() ||
                            $request->wantsJson() ||
                            $request->header('Content-Type') == 'application/json';

            // Ensure user is authenticated
            if (!Auth::check()) {
                if ($isJsonRequest) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You must be logged in to submit a review.'
                    ], 401);
                }
                return redirect()->route('login')->with('error', 'You must be logged in to submit a review.');
            }

            Log::info('Review submission received', [
                'user_id' => Auth::id(),
                'doctor_id' => $request->doctor_id,
                'appointment_id' => $request->appointment_id,
                'request_type' => $isJsonRequest ? 'JSON' : 'FORM',
                'content_type' => $request->header('Content-Type')
            ]);

            // Validate request
            $validated = $request->validate([
                'doctor_id' => 'required|exists:doctors,DoctorID',
                'appointment_id' => 'nullable|exists:appointments,AppointmentID',
                'doctor_rating' => 'required|integer|min:1|max:5',
                'service_rating' => 'nullable|integer|min:1|max:5',
                'cleanliness_rating' => 'nullable|integer|min:1|max:5',
                'staff_rating' => 'nullable|integer|min:1|max:5',
                'wait_time_rating' => 'nullable|integer|min:1|max:5',
                'feedback' => 'required|string|max:500',
                'is_anonymous' => 'sometimes|boolean',
            ]);

            // Check if user has already reviewed this appointment
            // Only check if appointment_id is provided and not null or empty
            $appointmentId = $request->appointment_id;
            if (!empty($appointmentId)) {
                Log::info('Checking for existing review', [
                    'user_id' => Auth::id(),
                    'appointment_id' => $appointmentId
                ]);

                $existingReview = Rating::where('user_id', Auth::id())
                    ->where('appointment_id', $appointmentId)
                    ->first();

                if ($existingReview) {
                    Log::warning('Duplicate review attempt', [
                        'user_id' => Auth::id(),
                        'appointment_id' => $appointmentId,
                        'existing_review_id' => $existingReview->id
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'You have already submitted a review for this appointment.'
                    ], 422);
                }
            }

            // Create new rating
            $rating = new Rating([
                'user_id' => Auth::id(),
                'doctor_id' => $validated['doctor_id'],
                'appointment_id' => $validated['appointment_id'] ?? null,
                'doctor_rating' => $validated['doctor_rating'],
                'service_rating' => $validated['service_rating'] ?? null,
                'cleanliness_rating' => $validated['cleanliness_rating'] ?? null,
                'staff_rating' => $validated['staff_rating'] ?? null,
                'wait_time_rating' => $validated['wait_time_rating'] ?? null,
                'feedback' => $validated['feedback'],
                'is_anonymous' => $validated['is_anonymous'] ?? false,
                'status' => 'approved', // Auto-approve for testing
            ]);

            $rating->save();

            // If request is a JSON request, return JSON response with updated ratings info
            if ($isJsonRequest) {
                // Get the doctor to calculate updated average rating
                $doctor = Doctor::findOrFail($validated['doctor_id']);
                $avgRating = $doctor->getAverageRatingAttribute() ?? 0;
                $ratingCount = $doctor->ratings->where('status', 'approved')->whereNotNull('doctor_rating')->count();

                // Add user information for the review display
                $reviewData = $rating->toArray();
                $reviewData['user_name'] = Auth::user()->FullName ?? 'Anonymous User';
                $reviewData['created_at'] = $rating->created_at->format('Y-m-d H:i:s');
                $reviewData['user'] = [
                    'FullName' => Auth::user()->FullName ?? 'Anonymous User',
                    'UserID' => Auth::id()
                ];

                return response()->json([
                    'success' => true,
                    'message' => 'Your review has been submitted and is pending approval.',
                    'rating' => $rating,
                    'review' => $reviewData,
                    'avgRating' => $avgRating,
                    'ratingCount' => $ratingCount
                ]);
            }

            // Otherwise redirect with success message
            return redirect()->back()->with('success', 'Your review has been submitted and is pending approval.');

        } catch (\Exception $e) {
            Log::error('Error creating review: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson() || $request->header('Content-Type') == 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating review: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error creating review: ' . $e->getMessage());
        }
    }

    /**
     * Display public doctor ratings
     *
     * @param  int  $id  Doctor ID
     * @return \Illuminate\Http\Response
     */
    public function doctorRatings($id)
    {
        // Find the doctor with that ID
        $doctor = Doctor::with('user')->findOrFail($id);

        // Get approved ratings for this doctor
        $ratings = Rating::with('user')
            ->where('doctor_id', $id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate average ratings for different aspects
        $avgRatings = [
            'doctor' => $ratings->whereNotNull('doctor_rating')->avg('doctor_rating'),
            'service' => $ratings->whereNotNull('service_rating')->avg('service_rating'),
            'cleanliness' => $ratings->whereNotNull('cleanliness_rating')->avg('cleanliness_rating'),
            'staff' => $ratings->whereNotNull('staff_rating')->avg('staff_rating'),
            'wait_time' => $ratings->whereNotNull('wait_time_rating')->avg('wait_time_rating'),
        ];

        // Get rating distribution (how many 5-stars, 4-stars, etc.)
        $distribution = [
            1 => $ratings->where('doctor_rating', 1)->count(),
            2 => $ratings->where('doctor_rating', 2)->count(),
            3 => $ratings->where('doctor_rating', 3)->count(),
            4 => $ratings->where('doctor_rating', 4)->count(),
            5 => $ratings->where('doctor_rating', 5)->count(),
        ];

        return view('pages.doctor_ratings', compact('doctor', 'ratings', 'avgRatings', 'distribution'));
    }

    /**
     * API endpoint to fetch paginated reviews for a doctor
     *
     * @param int $doctorId
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiGetDoctorReviews($doctorId, Request $request)
    {
        try {
            // Log request details
            Log::info('API Reviews request received', [
                'doctor_id' => $doctorId,
                'page' => $request->get('page', 2),
                'request_ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Verify doctor exists
            $doctor = Doctor::findOrFail($doctorId);
            Log::info('Doctor found', ['doctor_id' => $doctor->DoctorID, 'name' => $doctor->user->FullName ?? 'Unknown']);

            // Get requested page, default to page 2 (since page 1 is loaded initially)
            $page = $request->get('page', 2);
            $perPage = 10; // Number of reviews per page
            $initialPageSize = 5; // Number of reviews loaded on initial page

            // First, get total count of approved ratings for this doctor
            $totalReviews = Rating::where('doctor_id', $doctorId)
                ->where('status', 'approved')
                ->whereNotNull('doctor_rating')
                ->count();

            Log::info('Total reviews count', ['doctor_id' => $doctorId, 'total_reviews' => $totalReviews]);

            // Calculate offset differently:
            // Page 2 should start after the initial 5 reviews
            // Further pages should continue with 10 per page
            $offset = ($page == 2) ? $initialPageSize : $initialPageSize + ($page - 2) * $perPage;

            Log::info('Calculated offset', [
                'page' => $page,
                'offset' => $offset,
                'per_page' => $perPage,
                'initial_page_size' => $initialPageSize
            ]);

            // Get approved ratings for this doctor with pagination
            $reviews = Rating::with(['user' => function($query) {
                $query->withDefault([
                    'FullName' => 'Anonymous User',
                    'Email' => 'anonymous@example.com'
                ]);
            }])
            ->where('doctor_id', $doctorId)
            ->where('status', 'approved')
            ->whereNotNull('doctor_rating')
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->take($perPage) // Take one regular page worth
            ->get();

            Log::info('Reviews fetched', [
                'doctor_id' => $doctorId,
                'page' => $page,
                'count' => $reviews->count(),
                'first_review_id' => $reviews->first() ? $reviews->first()->id : null
            ]);

            // Calculate if there are more reviews available after this batch
            $hasMore = ($offset + $reviews->count()) < $totalReviews;

            return response()->json([
                'success' => true,
                'reviews' => $reviews,
                'has_more' => $hasMore,
                'page' => $page,
                'debug' => [
                    'doctor_id' => $doctorId,
                    'page' => $page,
                    'per_page' => $perPage,
                    'initial_page_size' => $initialPageSize,
                    'offset' => $offset,
                    'total_reviews' => $totalReviews,
                    'fetched_count' => $reviews->count(),
                    'has_more' => $hasMore
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching reviews: ' . $e->getMessage(), [
                'doctor_id' => $doctorId ?? 'unknown',
                'page' => $request->get('page', 'unknown'),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching reviews: ' . $e->getMessage(),
                'debug_info' => [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }
}