<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DeleteReview
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $review = $request->route('review');

        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        if ($user->id === $review->user_id || $user->hasRole('admin')) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
