<?php

namespace App\Http\Resources;

use App\Models\Application;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @mixin Application
 */
class ApplicationResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Application $this */
        return array_merge(
            parent::toArray($request),
            [
                'meta' => [],
            ]
        );
    }
}
