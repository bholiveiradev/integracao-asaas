<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'gateway_name',
        'reference',
        'description',
        'amount',
        'billing_type',
        'status',
        'due_date',
        'installment_count',
        'external_url',
    ];

    public static function booted()
    {
        parent::booted();

        static::creating(function (Payment $model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
