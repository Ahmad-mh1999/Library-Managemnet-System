<?php

namespace App\Http\Traits;

use Exception;
use App\Models\Book;
use App\Events\BookBorrowed;

use App\Events\BookReturned;
use Illuminate\Support\Facades\DB;
use function App\Helpers\formateDate;

trait ManageBorrowOperation
{
    // check book availability
    public function checkAvailability()
    {
        return $this->amount > 0;
    }

    // Decreases the book amount by 1 when a book is borrowed.
    public function decreaseBookAmount()
    {
        if ($this->checkAvailability()) {
            $this->decrement('amount');
        }
    }
    
    //  Increases the book amount by 1 when a book is returned.
    public function increaseBookAmount()
    {
        $this->increment('amount');
    }

    public function borrowMultipleBooks($request, $user)
    {
        try {
            DB::beginTransaction();
            $book_ids = $request->input('book_id');
            $books = Book::findMany($book_ids);
            $unavailableBooks = [];
            $borrowedBooks = [];
            foreach ($books as $book) {
                if (!$book->checkAvailability()) {
                    $unavailableBooks[] = $book->title;
                    continue;
                    // throw new Exception("Book with ID {$book->id} is not available.");
                }
                $borrowedBooks[] = $book->title;
                $dateFormate = formateDate(now(),'Y-m-d H:i:s');
                $user->books()->attach($book->id, ['borrowed_at' =>  $dateFormate]);
                
                $book->decreaseBookAmount(); 
            }
            
            DB::commit();
            event(new BookBorrowed($borrowedBooks));
            return [
                'success' => true,
                'borrowed_books' => $borrowedBooks,
                'unavailable_books' => $unavailableBooks,
                'message' => 'Processed book borrowing with some books unavailable.'
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            return [
                'success' => false,
                'error' => $th->getMessage(),
                'borrowed_books' => [],
                'unavailable_books' => $unavailableBooks,
            ];
        }

    }

    public function returnBorrowedBooks($request, $user){

        try {
            DB::beginTransaction();
            $book_ids = $request->input('book_id');
            $books = $user->books()->findMany($book_ids);
            $returnedBooks = [];
            foreach ($books as $book) {
                $user->books()->detach($book->id);
                $book->increaseBookAmount();
                $returnedBooks[] =$book->title ;
            }
    
            DB::commit();
            event(new BookReturned($returnedBooks));
            return response()->json(['message' => 'All books returned successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}


