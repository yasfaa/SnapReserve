<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Inertia\Inertia;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function showUploadPage()
    {
        return Inertia::render('Post');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'caption' => 'required|string|max:255',
            'gambar' => 'required|mimes:jpeg,png,jpg,gif,webp,mp4,avi,mov|max:153600',
        ]);

        $post = new Post();
        $post->user_id = auth()->user()->id;
        $post->caption = $request->caption;

        if ($request->hasFile('gambar')) {
            $fileType = $request->file('gambar')->getMimeType();
            $folder = str_contains($fileType, 'video') ? 'public/post_videos' : 'public/post_images';

            $path = $request->file('gambar')->store($folder);
            $post->path = basename($path);
        }

        $post->save();

        return redirect()->route('dashboard')->with('success', 'Post uploaded successfully.');
    }


    public function addComment(Request $request, $post_id)
    {
        try {
            $request->validate([
                'comment' => 'required|string'
            ]);

            $comment = new Comment();
            $comment->comment = $request->comment;
            $comment->post_id = $post_id;
            $comment->user_id = Auth::id();
            $comment->save();

            return response()->json(['status' => 'success', 'message' => 'Komentar berhasil ditambahkan'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteComment($commentId)
    {
        try {
            $comment = Comment::findOrFail($commentId);

            if ($comment->user_id !== Auth::id()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
            }

            $comment->delete();

            return response()->json(['status' => 'success', 'message' => 'Komentar berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function likePost($postId)
    {
        try {
            $post = Post::findOrFail($postId);
            $userId = Auth::id();

            $like = Like::where('post_id', $postId)->where('user_id', $userId)->first();

            if ($like) {
                $like->delete();
                return response()->json(['status' => 'success', 'message' => 'Like dihapus'], 200);
            } else {
                $newLike = new Like();
                $newLike->post_id = $postId;
                $newLike->user_id = $userId;
                $newLike->save();
                return response()->json(['status' => 'success', 'message' => 'Post berhasil dilike'], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getPostDetail($postId)
    {
        $user = auth()->user();

        $post = Post::with(['comments', 'likes'])->where('id', $postId)->first();

        if (!$post) {
            return response()->json(['message' => 'Post tidak ditemukan'], 404);
        }

        $likeCount = $post->likes->count();
        $hasLiked = $post->likes()->where('user_id', $user->id)->exists();
        $comments = $post->comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'username' => $comment->user->name,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at,
            ];
        });

        return Inertia::render('Profile', [
            'post' => [
                'id' => $post->id,
                'username' => $post->user->name,
                'caption' => $post->caption,
                'image_url' => asset('storage/post_images/' . $post->path),
                'like_count' => $likeCount,
                'has_liked' => $hasLiked,
                'comments' => $comments,
                'created_at' => $post->created_at,
                'profile_image_url' => $post->user->path ? asset('storage/profile_images/' . $post->user->path) : null,
            ],
        ]);
    }

    public function getArchivedPhotos(Request $request)
    {
        try {
            $query = Post::where('status', 'archived');

            if ($request->query('start_date')) {
                $query->where('created_at', '>=', $request->query('start_date'));
            }

            if ($request->query('end_date')) {
                $query->where('created_at', '<=', $request->query('end_date'));
            }

            $archivedPhotos = $query->get(['path', 'caption', 'created_at']);

            // Format hasil untuk ditampilkan
            $result = $archivedPhotos->map(function ($photo) {
                return [
                    'path' => asset('storage/post_images/' . $photo->path),
                    'caption' => $photo->caption,
                    'tanggal' => $photo->created_at,
                ];
            });

            return response()->json([
                'status' => 'success',
                'archived_photos' => $result,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
