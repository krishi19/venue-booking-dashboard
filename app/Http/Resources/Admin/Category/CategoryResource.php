<?php

namespace App\Http\Resources\Admin\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'id' => $this->id,
            'en_name' => $this->en_name,
            'np_name' => $this->np_name,
            'category_id' => $this->category_id,
            'status'=>$this->status,
            'image_url'=>$this->image?$this->image_url:null,
            'image'=>$this->image,
            'total_venues'=>$this->total_venues,
            'created_at'=>$this->created_at
        ];
    }
}
