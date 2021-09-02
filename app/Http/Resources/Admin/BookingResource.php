<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'id'=>$this->id,
            'full_name'=>$this->full_name,
            'email'=>$this->email,
            'contact'=>$this->contact,
            'date'=>$this->date,
            'no_of_people'=>$this->no_of_people,
            'status'=>$this->status,
            'owner_name'=>$this->venue->venueOwner->name,
            'owner_mobile'=>$this->venue->venueOwner->mobile,
            'venue_name'=>$this->venue->name,
            'venue_address'=>$this->venue->address,
        ];
    }
}
