<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageBank extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'package_banks';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    public $incrementing = true;


    protected $fillable = [
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'logo'
    ];
}
