<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Auther;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ReviewRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with('reviewable')->get();
        return $this->customeResponse(ReviewResource::collection($reviews),'Done',200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeBookReview(ReviewRequest $request,Book $book)
    {
        $review = $book->reviews()->create([
            'review' => $request->review,
        ]);
        return $this->customeResponse(new ReviewResource($review), 'review Created Successfuly', 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function storeAutherReview(ReviewRequest $request,Auther $auther)
    {
        $review = $auther->reviews()->create([
            'review' => $request->review,
        ]);
        return $this->customeResponse(new ReviewResource($review), 'review Created Successfuly', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        return $this->customeResponse(new ReviewResource($review), 'Done', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReviewRequest $request, Review $review)
    { 
        $review->review = $request->input('review') ?? $review->review;
        $review->save();
        return $this->customeResponse(new ReviewResource($review), 'Review updated Successfuly', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {

        $review->delete();
        return $this->customeResponse("", 'review deleted successfully', 200);
    }
}
