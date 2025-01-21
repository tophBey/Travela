<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    public $incrementing = true;





    protected $fillable = [
        'name',
        'icon',
        'slug',
    ];

    public function tour(){
        return $this->hasMany(PackageTour::class,'category_id');
    }
}
