<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $posts = Post::with('user')
            ->when(
                $request->search,
                fn($query, $search) =>
                $query->where('title', 'like', '%' . $search . '%')
            )
            ->when(
                $request->user,
                fn($query, $userId) =>
                $query->where('user_id', $userId)
            )
            ->inRandomOrder()
            ->paginate(18);

        return view('welcome', [
            'posts' => $posts,
            'users' => User::all()
        ]);
    }

    public function reviewStore(Request $request)
    {
        try {
            $request->validate([
                'post_id' => 'required|exists:posts,id',
                'rating' => 'required|integer|min:1|max:5',
            ]);

            switch ($request->rating) {
                case 1:
                    $review = 'bad experience';
                    break;
                case 2:
                    $review = 'some good experience';
                    break;
                case 3:
                    $review = 'good experience';
                    break;
                case 4:
                    $review = 'excellent experience';
                    break;
                case 5:
                    $review = 'much better experience';
                    break;
                default:
                    $review = '';
                    break;
            }

            Review::create([
                'user_id' => auth()->id(),
                'post_id' => $request->post_id,
                'rating' => $request->rating,
                'review' => $request->review ?? $review,
            ]);

            return response()->json(['type' => 'success', 'message' => 'Thanks for your feedback!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'type' => 'error',
                'errors' => $e->validator->errors()->all()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Review submission failed: ' . $e->getMessage());
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }
}
