<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     * @mixin Organization
     */
    public function toArray($request)
    {

        return
            [
                'id' =>$this->id,
                'title' => $this->title,
                'city' => $this->city,
                'country' => $this->country,
            ];
    }
}
