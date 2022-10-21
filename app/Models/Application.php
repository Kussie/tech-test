<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use App\Events\ApplicationCreated;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => ApplicationStatus::class,
    ];

    protected $dispatchesEvents = [
        'created' => ApplicationCreated::class,
    ];

    protected $hidden = [];

    protected $with = [
        'plan'
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function Address(): Attribute
    {
        return new Attribute(
            get: fn () => "{$this->address_1} {$this->address_2} {$this->city} {$this->state} {$this->postcode}"
        );
    }
}
