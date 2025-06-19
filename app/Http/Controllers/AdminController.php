<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'videos_count' => Video::count(),
            'users_count' => User::count(),
            'most_viewed' => Video::orderBy('views', 'desc')->take(5)->get(),
        ]);
    }

    public function users() {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function videos() {
        $videos = Video::all();
        return view('admin.videos.index', compact('videos'));
    }
}