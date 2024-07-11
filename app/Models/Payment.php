<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'amount',
        'billing_type',
        'status',
        'external_url',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
