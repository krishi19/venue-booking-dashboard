<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\VenueResource;
use App\Models\Category;
use App\Models\Venue;
use App\Traits\StoreImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VenueController extends Controller
{
    use StoreImage;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $venues = Venue::
        when($request->search, function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
            $q->orWhere('contact', 'like', '%' . $request->search . '%');
            $q->orWhere('address', 'like', '%' . $request->search . '%');
        })
            ->when(Auth::user()->role=='Venue Owner', function ($q) use ($request) {
            $q->whereAdminId(Auth::id());
            })
            ->with(['category', 'venueOwner'])
            ->orderBy('updated_at', 'Desc')->paginate($request->per_page);
        return VenueResource::collection($venues);
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
            'name' => 'required|string|max:255|unique:venues',
            'address' => 'required|string|max:255',
            'gps' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'cost' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
            'image_array' => 'required|array',
        ]);

        $venue = '';
        DB::transaction(function () use ($request, &$venue) {
            $images = [];
            if (is_array($request->image_array)) {
                foreach ($request->image_array as $image) {
                    $thumbnail_path = storage_path() . '/app/public/uploads/venues/thumbnails/';
                    $newName = $this->createThumbnail($image, 165, 160, $thumbnail_path, $name = null);
                    $images[] = $newName;
                }
            }
            $request['images'] = $images;
            $request['admin_id'] = Auth::user()->id;
            $venue = Venue::create(
                [
                    'name' => $request->name,
                    'admin_id' => $request->admin_id,
                    'contact' => $request->contact,
                    'category_id' => $request->category_id,
                    'gps' => $request->gps,
                    'cost' => $request->cost,
                    'capacity' => $request->capacity,
                    'address' => $request->address,
                    'images' => $request->images,
                    'description' => $request->description

                ])->fresh();

        });
        return response()->json([
            'message' => 'Success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Venue $venue)
    {
        return new VenueResource($venue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venue $venue)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:venues,name,' . $request->id,
            'address' => 'required|string|max:255',
            'gps' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'cost' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
            'image_array' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $venue) {
            $images = [];
            if (is_array($request->image_array)) {
                foreach ($request->image_array as $image) {
                    $newName = $image;
                    if (!in_array($image, (array)$venue->images) && $image != '[object Object]') {
                        $thumbnail_path = storage_path() . '/app/public/uploads/venues/thumbnails/';
                        $newName = $this->createThumbnail($image, 165, 160, $thumbnail_path, $name = null);
                    }
                    $newName != '[object Object]' ? array_push($images, $newName) : '';
                }
            }
            $request['images'] = $images;
            $venue->update(
                [
                    'name' => $request->name,
                    'contact' => $request->contact,
                    'category_id' => $request->category_id,
                    'gps' => $request->gps,
                    'cost' => $request->cost,
                    'capacity' => $request->capacity,
                    'address' => $request->address,
                    'images' => $request->images,
                    'description' => $request->description
                ]);

        });
        return response()->json([
            'message' => 'Success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venue $venue)
    {
        $venue->delete();
        return response()->json([
            'message' => 'Deleted Successfully'
        ]);
    }
}
