<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Category\CategoryResource;
use App\Models\Category;
use App\Traits\StoreImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    use StoreImage;
    public function getParentSubCategory()
    {
        return Category::latest()->get();
    }

    public function index(Request $request)
    {
        $categories = Category::
        when($request->search, function ($q) use ($request) {
            $q->where('en_name', 'like', '%' . $request->search . '%');
            $q->orWhere('np_name', 'like', '%' . $request->search . '%');
        })
            ->when($request->view_only_root == 'true', function ($q) use ($request) {
                $q->whereNull('category_id');
            })
            ->when($request->parent_category, function ($q) use ($request) {
                $q->where('category_id', $request->parent_category);
            })
            ->orderBy('updated_at','Desc')->get();
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'en_name' => 'required|string|max:255|unique:categories',
            ]);

        $category='';
        DB::transaction(function () use ($request,&$category) {
            $newName = '';
            if ($request->hasFile('image_file')) {
                $request->validate(['image_file' => 'mimes:jpeg,jpg,png,gif|max:5000']);

                $thumbnail_path = storage_path() . '/app/public/uploads/categories/thumbnails/';
                $newName = $this->createThumbnail($request->image_file, 165, 160, $thumbnail_path, $name = null);
            }

            $request['image']=$newName;

            $category = Category::create($request->only('en_name','np_name','category_id','image'))->fresh();

        });
        return response()->json([
            'data' => new CategoryResource($category)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->has('status')?
            $request->validate(['status'=>'required|boolean']):
            $request->validate([
                'en_name' => 'required|string|max:255|unique:categories,en_name,'.$request->id,
                'specifications'=>'nullable|string'
            ]);

        if ($request->has('status')) {
            $category->update(['status' => $request->status]);
            return response()->json(['data' => new CategoryResource($category)]);
        }

        if ($request->category_id){
            $parent=Category::findOrFail($request->category_id);
            if (in_array($category->id,$parent->parent_ids)){
                return response()->json(['message'=>'Selected parent category is already assigned on child categories hierarchy'],500);
            }
            if ($request->id==$request->category_id)
                return response()->json(['message'=>'Parent and child categories are same'],500);


        }
        $new_category='';
        DB::transaction(function () use ($request,&$new_category,$category) {
            $newName = $request->image_file;
            if ($request->hasFile('image_file') && $request->image_file != $category->image) {
                $request->validate(['image_file' => 'mimes:jpeg,jpg,png,gif|max:5000']);

                $thumbnail_path = storage_path() . '/app/public/uploads/categories/thumbnails/';
                $newName = $this->createThumbnail($request->image_file, 165, 160, $thumbnail_path, $name = null);
            }
            if (file_exists(storage_path() . '/app/public/uploads/categories/thumbnails/' . $category->image) && $request->image_file!= $category->image) {
                @unlink(storage_path() . '/app/public/uploads/categories/thumbnails/' . $category->image);
            }
            $request['image'] = $newName;
            $new_category = tap($category)->update($request->only('en_name', 'np_name', 'category_id', 'image'));
        });
        return response()->json([
            'data' => new CategoryResource($new_category)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'message' => 'Deleted Successfully'
        ]);
    }

//    public function getSubCategory($id)
//    {
//        $categories = Category::with('sub_category')->where('category_id', $id)->get();
//        return $categories;
//        return response([
//            'data' => new CategoryResource($categories)
//        ], Response::HTTP_CREATED);
//    }
//
//    // 10=>4=>1
//    public function getParentCategory($id)
//    {
//        $categories = Category::findOrFail($id);
//        $parent = Category::with('sub_category')->where('id', $categories->category_id)->first();
//        if (isset($parent)) {
//            $cat = Category::where('category_id', $parent->category_id)->get();
//            return response([
//                'error' => false,
//                'status_code' => 200,
//                'parent_selected_id' => $parent->category_id,
//                'data' => $cat
//            ], Response::HTTP_OK);
//        } else {
//            return response([
//                'status_code' => 404,
//                'error' => true,
//            ], Response::HTTP_OK);
//        }
//    }
}
