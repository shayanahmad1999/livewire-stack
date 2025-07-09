<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Review;
use App\Models\User;
use App\Notifications\PostRated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    // public function home(Request $request)
    // {
    //     $posts = Post::with('user')
    //         ->when(
    //             $request->search,
    //             fn($query, $search) =>
    //             $query->where('title', 'like', '%' . $search . '%')
    //         )
    //         ->when(
    //             $request->user,
    //             fn($query, $userId) =>
    //             $query->where('user_id', $userId)
    //         )
    //         ->inRandomOrder()
    //         ->paginate(18);

    //     return view('welcome', [
    //         'posts' => $posts,
    //         'users' => User::all()
    //     ]);
    // }

    public function home(Request $request)
    {
        $requestRating = $request->rating ? (float) $request->rating : null;

        $postsQuery = Post::with('user')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->when(
                $request->search,
                fn($query, $search) =>
                $query->where('title', 'like', '%' . $search . '%')
            )
            ->when(
                $request->user,
                fn($query, $userId) =>
                $query->where('user_id', $userId)
            );

        if ($requestRating !== null) {
            $postsQuery->whereHas('reviews', function ($query) use ($requestRating) {
                $query->select('post_id')
                    ->groupBy('post_id')
                    ->havingRaw('rating = ?', [$requestRating]);
            });
        }

        $posts = $postsQuery->inRandomOrder()->paginate(18);

        return view('welcome', [
            'posts' => $posts,
            'users' => User::all()
        ]);
    }

    public function reviewStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'post_id' => 'required|exists:posts,id',
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:500',
            ]);

            $existingReview = Review::where('user_id', auth()->id())
                ->where('post_id', $validated['post_id'])
                ->first();

            if ($existingReview) {
                return response()->json([
                    'type' => 'error',
                    'message' => __('You have already submitted feedback for this post.')
                ]);
            }

            $autoReview = match ($validated['rating']) {
                1 => 'Very bad experience',
                2 => 'Below average experience',
                3 => 'Average experience',
                4 => 'Great experience',
                5 => 'Outstanding experience',
            };

            $reviewText = $validated['review'] ?? $autoReview;

            Review::create([
                'user_id' => auth()->id(),
                'post_id' => $validated['post_id'],
                'rating' => $validated['rating'],
                'review' => $reviewText,
            ]);

            $post = Post::find($validated['post_id']);
            $rater = auth()->user();

            $post->user->notify(new PostRated($rater, $post, $validated['rating']));

            return response()->json([
                'type' => 'success',
                'message' => __('Thanks for your feedback!')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'type' => 'error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Review submission failed: ' . $e->getMessage());
            return response()->json([
                'type' => 'error',
                'message' => __('Something went wrong. Please try again.')
            ], 500);
        }
    }
}
