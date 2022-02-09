<?php

namespace App\Http\Controllers;

use App\Models\Cast;
use App\Models\User;
use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $movies = Movie::all();
        $series = Serie::all();
        $casts = Cast::all();

        return view('admin.index', compact('users', 'movies', 'series', 'casts'));
    }
}
