<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Category\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request){
        $type=$request->type;
        $categories= Category::when($type=='root',function ($q){
            $q->whereNull('category_id');
        })
        ->get();
        return CategoryResource::collection($categories);
    }
}
