<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePackageTourRequest;
use App\Models\PackageTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class PackageTourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packageTours = PackageTour::orderByDesc('id')->paginate(10);

        return view('admin.package_tours.index', compact('packageTours'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = PackageTour::orderByDesc('id');

        return view('admin.package_tours.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePackageTourRequest $request)
    {

        DB::transaction(function() use($request){
            $validated = $request->validated();


            if($request->hasFile('thumbnail')){
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails/' . date('Y/m/d'), 'public');
                $validated['thumbnail'] = $thumbnailPath;
            }

            $validated['slug'] = Str::slug($validated['name']);


            $packageTour = PackageTour::create($validated);

            if($request->hasFile('photos')){
                foreach($request->file('photos') as $photo){
                    $photosPath = $photo->file('photos')->store('package_photos/' . date('Y/m/d'), 'public');

                    $packageTour->package_photos()->create([
                        "photo" => $photosPath
                    ]);
                }
            }

            return redirect()->route('admin.package_tours.index');
            

        });

    }

    /**
     * Display the specified resource.
     */
    public function show(PackageTour $packageTour)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PackageTour $packageTour)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PackageTour $packageTour)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PackageTour $packageTour)
    {
        //
    }
}
