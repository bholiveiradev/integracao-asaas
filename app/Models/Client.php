<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'cpf_cnpj',
        'phone',
        'mobile_phone',
        'email',
    ];

    protected function cpfCnpj(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => preg_replace("/[^0-9]/", '', $value)
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function payment_gateway_settings(): HasMany
    {
        return $this->hasMany(PaymentGatewaySetting::class);
    }
}
