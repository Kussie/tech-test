<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory;

    public function plan(): HasOne
    {
        return $this->hasOne(Plan::class);
    }

    public function FullName(): Attribute
    {
        return new Attribute(
            get: fn () => "{$this->first_name} {$this->last_name}"
        );
    }
}
