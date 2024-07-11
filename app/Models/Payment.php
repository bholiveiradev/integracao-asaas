<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'reference',
        'amount',
        'billing_type',
        'status',
        'external_url',
    ];

    public static function booted()
    {
        parent::booted();

        self::creating(function (Payment $model) {
            $model->id = Str::uuid();
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
