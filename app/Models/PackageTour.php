<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageTour extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'package_tours';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    public $incrementing = true;



    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'about',
        'location',
        'price',
        'days',
        'category_id'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function package_photos(){
        return $this->hasMany(PackagePhoto::class);
    }

    // public function package_bookings(){
    //     return $this->hasMany(PackageBooking::class,'package_tour_id','id');
    // }
}
