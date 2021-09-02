<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded=[];
    private $idCollections = [];

    protected $casts=[
        'status'=>'boolean'
    ];

    public function specifications(){
        return $this->belongsToMany(Specification::class);
    }

    public function getImageUrlAttribute(){
        return image_url($this->image,'uploads/categories/thumbnails/');
    }
    public function subCategories()
    {
        return $this->hasMany(Category::class)->with('subCategories');
    }

    public function parentCategory(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function parentCategories()
    {
        return $this->belongsTo(Category::class, 'category_id')->with('parentCategories');
    }

    public function getParentIdsAttribute()
    {
        if ($this->parentCategories)
        $this->collectParentIds($this->parentCategories);
        return $this->idCollections;
    }

    public function collectParentIds($category)
    {
        $this->idCollections[] = $category['id'];
        if ($category['parentCategories']) {
            $this->collectParentIds($category['parentCategories']);
        }
    }

    public function scopeParent($query)
    {
        return $query->whereNull('category_id');
    }
    public function getSubCategoriesCountAttribute(){
        return $this->subCategories->count();
    }
//    public function getProductsCountAttribute(){
//        return $this->products->count();
//    }
public function venues(){
        return $this->hasMany(Venue::class);
}
public function getTotalVenuesAttribute(){
        return $this->venues()->count();
}
}
