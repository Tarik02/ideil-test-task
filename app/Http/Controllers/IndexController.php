<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $places = Place::with('defaultPhoto')->get();

        return view('index', compact('places'));
    }
}
