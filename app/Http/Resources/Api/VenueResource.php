<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'address' => $this->address,
            'admin_id' => $this->admin_id,
            'capacity' => $this->capacity,
            'category' => $this->category->en_name,
            'category_id' => $this->category_id,
            'contact' => $this->contact,
            'cost' => $this->cost,
            'created_at' => $this->created_at,
            'description' => $this->description,
            'gps' => $this->gps,
            'id' => $this->id,
            'images' => $this->images,
            'image_urls' => $this->image_urls,
            'name' => $this->name,
            'owner_name'=>$this->venueOwner->name,
            'owner_mobile'=>$this->venueOwner->mobile,
            'distance'=>round($this->distance,2)
        ];
    }
}
