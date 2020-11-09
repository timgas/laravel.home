<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * @mixin User
         */
        return [
            'id' => $this->id,
            'role' => $this->role,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'country' => $this->country,
            'city' => $this->city,
            'phone' => $this->phone
        ];
    }
}
