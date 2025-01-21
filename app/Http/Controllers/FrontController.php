<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePackageBookingCheckoutRequest;
use App\Http\Requests\StorePackageBookingRequest;
use App\Http\Requests\UpdatePackageBankRequest;
use App\Http\Requests\UpdatePackageBookingRequest;
use App\Models\Category;
use App\Models\PackageBank;
use App\Models\PackageBooking;
use App\Models\PackageTour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    public function index(){

        $categories = Category::orderByDesc('id')->get();

        $package_tours = PackageTour::orderByDesc('id')->take(3)->get();

        return view('frontend.index', compact('package_tours', 'categories'));
    }

    public function category(Category $category){

        return view('frontend.category', compact('category'));
    }


    public function details(PackageTour $packageTour){

        $latestPhotos = $packageTour->package_photos()->orderByDesc('id')->take(3)->get(); 


        return view('frontend.details', compact('packageTour', 'latestPhotos'));
    }


    public function book(PackageTour $packageTour){

        return view('frontend.book', compact('packageTour'));
    }

    public function book_store(StorePackageBookingRequest $request, PackageTour $packageTour){

        $user = Auth::user();
        $bank = PackageBank::orderByDesc('id')->first();
        $packageBookingId = null;

        DB::transaction(function() use($request, $packageTour, $user, $bank, &$packageBookingId){

            $validated = $request->validated();

            $startDate = new Carbon($validated['start_date']);
            $totalDays = $packageTour->days - 1; //10, 11, 12

            $endDate = $startDate->addDays($totalDays);
            $subTotal = $packageTour->price * $validated['quantity'];
            $insurance = 300000 * $validated['quantity'];
            $tax = $subTotal * 0.10;

            $validated['end_date'] = $endDate;
            $validated['user_id'] = $user->id;
            $validated['is_paid'] = false;
            $validated['proof'] = 'dummytrx.png';
            $validated['package_tour_id'] = $packageTour->id;
            $validated['package_bank_id'] = $bank->id;
            $validated['insurance'] = $insurance;
            $validated['tax'] = $tax;
            $validated['sub_total'] = $subTotal;
            $validated['total_amount'] = $subTotal + $tax + $insurance;

            $packageBooking = PackageBooking::create($validated);
            $packageBookingId = $packageBooking->id;
        });

        if($packageBookingId){
            return redirect()->route('front.choose_bank', $packageBookingId);
        }else{
            return back()->withErrors('Failed to create Booking');
        }

    }


    public function choose_bank(PackageBooking $packageBooking){

        $user = Auth::user();
        // dd($packageBooking);
        if($packageBooking->user_id != $user->id){
            abort(403);
        }

        $banks = PackageBank::all();
        return view('frontend.choose_bank', compact('banks', 'packageBooking'));
    }

    public function choose_bank_store(UpdatePackageBookingRequest $request, PackageBooking $packageBooking){
        
        $user = Auth::user();
        // dd($packageBooking);
        if($packageBooking->user_id != $user->id){
            abort(403);
        }

        DB::transaction(function () use($request, $packageBooking, $user) {
            $validated = $request->validated();

            $packageBooking->update([
                "package_bank_id" => $validated['package_bank_id']
            ]);
        });

        return redirect()->route('front.book_payment', $packageBooking->id);//id
    }

    public function book_payment(PackageBooking $packageBooking){

        // dd($packageBooking);

        // $packageBooking = PackageBooking::find($packageBooking);
       
        return view('frontend.book_payment', compact('packageBooking'));
    }

    public function book_payment_store(StorePackageBookingCheckoutRequest $request, PackageBooking $packageBooking){
         
        $user = Auth::user();
        if($packageBooking->user_id != $user->id){
            abort(403);
        }

        DB::transaction(function() use($request, $packageBooking, $user){
            $validated = $request->validated();

            if($request->hasFile('proof')){
                $proofPath = $request->file('proof')->store('proofs', 'public');
                $validated['proof'] = $proofPath;
            }

            $packageBooking->update($validated);
        });

        return redirect()->route('front.book_finish',);
    }


    public function book_finish(){

        return view('frontend.book_finish');
    }
}
