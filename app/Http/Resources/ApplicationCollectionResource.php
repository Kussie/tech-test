<?php

namespace App\Http\Resources;

use App\Models\Application;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Application
 */
class ApplicationCollectionResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Application $this */
        $application = [
            'application_id' => $this->id,
            'customer_name' => $this->customer->full_name,
            'address' => $this->address,
            'plan_type' => $this->plan->type,
            'plan_name' => $this->plan->name,
            'state' => $this->state,
            'monthly_cost' => $this->plan->cost,
            'order_id' => $this->status->value === 'completed' ? $this->order_id : null,
        ];
        /* The readme states to only the order_id if the application is in the 'completed' state.
            However, as a personal preference i dislike APIs where the structure of the response changes
            So I have included the order_id in all responses, but set it to null if the application is not in the 'completed' state.

            This is a personal preference, and i would be happy to change this if needed, an example of how this
            could be done is below, assuming the order_id field above is removed first.

            if ($this->status->value === 'completed') {
                Arr::add($application, 'order_id', $this->order_id);
            }
         */

        return array_merge(
            $application,
            [
                'meta' => [
                    'links' => [
                        'self' => route('applications.show', [$this->id]),
                    ],
                ],
            ]
        );
    }
}
