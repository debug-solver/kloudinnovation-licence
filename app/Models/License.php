<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;


    protected $fillable = [
        'item_id',
        'license_key',
        'purchase_code',
        'amount',
        'supported_until',
        'support_amount',
        'buyer',
        'purchase_count',
        'domain',
        'url',
        'ip',
        'root_path'
    ];


    protected $casts = [
        'sold_at' => 'timestamp'
    ];
}
