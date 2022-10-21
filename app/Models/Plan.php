<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    protected function Cost(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => [
                'cents' => $this->monthly_cost,
                'formatted' => '$' . number_format($this->monthly_cost / 100, 2),
            ],
        );
    }
}
