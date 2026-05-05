<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    
    public function create(Business $business)
    {
        // Prevent providers from reviewing their own business
        abort_if(Auth::id() === $business->user_id, 403, 'Cannot review your own business.');

        // One review per user per business
        $existing = Review::where('business_id', $business->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return redirect()->route('businesses.show', $business)
                ->with('info', 'You have already reviewed this business.');
        }

        return view('reviews.create', compact('business'));
    }

    public function store(Request $request, Business $business)
    {
        abort_if(Auth::id() === $business->user_id, 403);

        $validated = $request->validate([
            'rating'                   => 'required|integer|between:1,5',
            'quality_rating'           => 'nullable|integer|between:1,5',
            'responsiveness_rating'    => 'nullable|integer|between:1,5',
            'punctuality_rating'       => 'nullable|integer|between:1,5',
            'professionalism_rating'   => 'nullable|integer|between:1,5',
            'title'                    => 'nullable|string|max:255',
            'body'                     => 'required|string|min:20',
            'service_used'             => 'nullable|string|max:255',
            'service_date'             => 'nullable|date|before_or_equal:today',
            'price_paid'               => 'nullable|numeric|min:0',
            'would_hire_again'         => 'boolean',
        ]);

        $business->reviews()->create([
            ...$validated,
            'user_id' => Auth::id(),
            'status'  => 'pending',
        ]);

        return redirect()->route('businesses.show', $business)
            ->with('success', 'Thank you! Your review is pending approval.');
    }

    /**
     * Business owner responds to a review.
     */
    public function respond(Request $request, Review $review)
    {
        $business = $review->business;
        abort_if(Auth::id() !== $business->user_id, 403);

        $request->validate(['response' => 'required|string|max:1000']);

        $review->update([
            'owner_response'      => $request->response,
            'owner_responded_at'  => now(),
        ]);

        return back()->with('success', 'Response posted.');
    }

    /**
     * Mark a review as helpful.
     */
    public function helpful(Review $review)
    {
        $review->helpfulVotes()->toggle(Auth::id());
        $review->update(['helpful_count' => $review->helpfulVotes()->count()]);
        return back();
    }
}
