<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $email
 * @property mixed $type
 * @property mixed $status
 * @property mixed $partner_id
 * @property mixed $worker_id
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class UserResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'type' => $this->type?->value,
            'status' => $this->status?->value,
            'partner_id' => $this->partner_id,
            'worker_id' => $this->worker_id,
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
