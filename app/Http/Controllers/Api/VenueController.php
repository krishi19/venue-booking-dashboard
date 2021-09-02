<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\VenueResource;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $venues = Venue::
        when($request->search, function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
                $q->orWhere('contact', 'like', '%' . $request->search . '%');
                $q->orWhere('address', 'like', '%' . $request->search . '%');
            });
        })
            ->with(['category','venueOwner'])
            ->when($request->from_price&&$request->to_price,function ($q) use ($request) {
                $q->whereBetween('cost',[$request->from_price,$request->to_price]);
            })
            ->when($request->category_id,function ($q) use ($request) {
                $q->whereCategoryId($request->category_id);
            })
            ->when($request->type!='nearby',function ($q){
                $q->latest();
            })
            ->when($request->per_page=='',function ($q) use ($request) {
                return $q->get();
            })
            ->when($request->per_page,function ($q) use ($request) {
               return $q->paginate($request->per_page);
            });
        if ($request->type=='nearby'){
            $markers = collect($venues)->map(function($venue) use ($request, $venues) {
                $venue_gps=json_decode($venue->gps,true);
                $venue['distance'] = $this->calculateDistanceBetweenTwoAddresses($venue_gps['lat'], $venue_gps['lng'], $request->user_lat, $request->user_lng);

                return $venue;
            });
            $markers= $markers->sortBy('distance');
            return VenueResource::collection($markers);
        }
        else
        return VenueResource::collection($venues);
    }

    private function calculateDistanceBetweenTwoAddresses($lat1, $lng1, $lat2, $lng2){
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);

        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        $delta_lat = $lat2 - $lat1;
        $delta_lng = $lng2 - $lng1;

        $hav_lat = (sin($delta_lat / 2))**2;
        $hav_lng = (sin($delta_lng / 2))**2;

        $distance = 2 * asin(sqrt($hav_lat + cos($lat1) * cos($lat2) * $hav_lng));

        $distance = 6371*$distance;
        // If you want calculate the distance in miles instead of kilometers, replace 6371 with 3959.

        return $distance;
    }
    public function show(Venue $venue){
        return new VenueResource($venue);

    }
}
