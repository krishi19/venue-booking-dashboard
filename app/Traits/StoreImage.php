<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

trait StoreImage {

    /**
     * @param Request $request
     * @return $this|false|string
     */

    public function storeImage($path,$image, $name)
    {
        $ext = $image->getClientOriginalExtension();
        $newName = strtolower(substr($name,0,3)) . time() . uniqid() .Auth::id(). '.' . $ext;
        $image->storeAs($path, $newName);

        return $newName;
    }

    public function createThumbnail($image,$width,$height,$thumbnail_path,$name)
    {
        $ext = strtolower($image->getClientOriginalExtension());
        $newName =$name?$name: time() . uniqid(). '.' . strtolower($ext);
        $img = Image::make($image)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        if (!file_exists($thumbnail_path)) {
            mkdir($thumbnail_path, 0755, true);
        }
        $img->save($thumbnail_path.$newName);
        return $newName; 
    }
}