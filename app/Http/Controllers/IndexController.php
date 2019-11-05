<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $places = Place::with('media')->get();

        return view('index', compact('places'));
    }
}
