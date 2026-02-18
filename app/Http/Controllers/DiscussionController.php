<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discussion;
use App\Models\Replies;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    // Membuat diskusi baru (POST)
    public function openDiskusi(Request $request) {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'content' => 'required|string',
        ]);

        $discussion = Discussion::create([
            'course_id' => $request->course_id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return response()->json($discussion, 201);
    }

    // Membalas diskusi (POST)
    public function balasDiskusi(Request $request, $id) {
        $request->validate([
            'content' => 'required|string',
        ]);

        $reply = Replies::create([
            'discussion_id' => $id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);
        
        return response()->json($reply, 201);
    }
}