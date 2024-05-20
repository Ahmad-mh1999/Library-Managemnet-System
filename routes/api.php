<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AutherController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes accessible only to users
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {


    Route::get('user/{id}', [UserController::class, 'show']);
    Route::put('update-user/{id}', [UserController::class, 'update']);
    Route::get('/notifications', [NotificationController::class, 'showUnreadNotification']);
    Route::put('/read_notification/{id}', [NotificationController::class, 'markNotificationAsRead']);
});

// book and author routs that admin can only access them
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('add-book', [BookController::class, 'store']);
        Route::put('update-book/{id}', [BookController::class, 'update']);
        Route::delete('delete-book/{id}', [BookController::class, 'destroy']);
    });

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

        Route::post('add-author', [AutherController::class, 'store']);
        Route::put('update-author/{id}', [AutherController::class, 'update']);
        Route::delete('delete-author/{id}', [AutherController::class, 'destroy']);

});

// all user can dela with this routs :
Route::middleware('auth:sanctum')->group(function () {

    Route::get('books', [BookController::class, 'index']);
    Route::get('book/{id}', [BookController::class, 'show']);
    Route::get('authers', [AutherController::class, 'index']);
    Route::get('author/{id}', [AutherController::class, 'show']);
    Route::post('/borrow-book', [BorrowController::class, 'borrowBook']);
    Route::post('/return-book', [BorrowController::class, 'returnBook']);
    Route::get('/user-books', [BorrowController::class, 'showUserBooks']);
    Route::get('/reviews',[ReviewController::class, 'index']);
    Route::post('/add-book-review/{id}',[ReviewController::class, 'storeBookReview']); 
    Route::post('/add-author-review/{id}',[ReviewController::class, 'storeAutherReview']);
    Route::get('/review/{review}',[ReviewController::class, 'show']);
    Route::put('/update-review/{id}',[ReviewController::class, 'update'])->middleware('is_reviewer');
    Route::delete('/delete-review/{id}',[ReviewController::class, 'destroy'])->middleware('delete_review');


});






