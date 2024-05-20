<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\BorrowRequest;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ManageBorrowOperation;
use App\Http\Resources\BookBorrowingResource;
use App\Http\Traits\ApiResponseTrait;

class BorrowController extends Controller
{
    use ManageBorrowOperation,ApiResponseTrait;

    public function borrowBook(BorrowRequest $request){
        $user = Auth::user();
        $response = $this->borrowMultipleBooks($request, $user);
        
        return $this->customeResponse(new BookBorrowingResource($response), 'updated Successfuly', 200);
        
    }

    public function returnBook(BorrowRequest $request){
        $user = Auth::user();
        $this->returnBorrowedBooks($request, $user);
        return response()->json(['message' => 'book retuned successfully'], 200);
    }
    

    public function showUserBooks(){
        $user_id = Auth::user()->id;
        $user = User::where('id',$user_id )->first();
        $books = $user->books()->get();
        return $this->customeResponse(BookResource::collection($books),'Done',200);
    }

}
