<?php

namespace App\Http\Controllers;

use App\Models\Cast;
use Illuminate\Http\Request;

class CastController extends Controller
{
    public function index()
    {
        $casts = Cast::orderBy('created_at', 'desc')->paginate(18);

        return view('cast.index', compact('casts'));
    }

    public function show(Cast $cast)
    {
        return view('cast.show', compact('cast'));
    }
}
