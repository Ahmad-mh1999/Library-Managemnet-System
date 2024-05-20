<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Events\BookAdded;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\BookResource;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\UpdateBookRequest;

class BookController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Cache::remember('books',120,function () {
            return Book::with('authers')->get();
        });
        return $this->customeResponse(BookResource::collection($books),'Done',200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $book = Book::create([
            'title' => $request->title,
            'date_of_publication' => $request->date_of_publication,
            'amount' => $request->amount,
        ]);
        if ($request->has('auther_id')) {
            $book->authers()->attach($request->input('auther_id'));
        }
        event(new BookAdded($book));
        return $this->customeResponse(new BookResource($book), ' Created Successfuly', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('authers');
        return $this->customeResponse(new BookResource($book), 'Done', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->title = $request->input('title') ?? $book->title;
        $book->date_of_publication = $request->input('date_of_publication') ?? $book->date_of_publication;
        $book->amount = $request->input('amount') ?? $book->amount;
        if ($request->has('auther_id')) {
            $book->authers()->sync($request->input('auther_id'));
        }
        $book->save();
        return $this->customeResponse(new BookResource($book), 'updated Successfuly', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return $this->customeResponse("", ' deleted successfully', 200);
    }
}
