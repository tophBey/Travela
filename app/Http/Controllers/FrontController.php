<?php

namespace App\Http\Controllers;

use App\Models\PackageTour;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(){

        $package_tours = PackageTour::orderByDesc('id')->take(3)->get();

        return view('frontend.index', compact('package_tours'));
    }
}
