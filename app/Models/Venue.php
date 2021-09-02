<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $guarded=[];
    protected $casts=[
        'images'=>'array',
        'gps'=>'array',
    ];
    public function venueOwner(){
        return $this->belongsTo(Admin::class,'admin_id');
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function getImageUrlsAttribute(){
        $images=[];
        foreach ((array)$this->images as $image)
            $images[]= image_url($image,'uploads/venues/thumbnails/');
        return $images;
    }
}
