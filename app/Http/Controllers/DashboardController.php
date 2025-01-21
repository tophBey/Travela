<?php

namespace App\Http\Controllers;

use App\Models\PackageBooking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    

    public function my_bookings(){

        return view('dashboard.my_booking');
    }


    public function booking_details(PackageBooking $packageBooking){


        return view('dashboard.booking_detail', compact('packageBooking'));
    }




}
