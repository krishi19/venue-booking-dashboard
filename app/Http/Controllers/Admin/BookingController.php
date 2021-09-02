<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::
        when($request->search, function ($q) use ($request) {
            $q->where('full_name', 'like', '%' . $request->search . '%');
            $q->orWhere('contact', 'like', '%' . $request->search . '%');
            $q->orWhere('location', 'like', '%' . $request->search . '%');
        })
            ->when(Auth::user()->role=='Venue Owner', function ($q) use ($request) {
                $q->whereHas('venue',function ($q){
                    $q->whereAdminId(Auth::id());
                });
            })
            ->when($request->booking_date, function ($q) use ($request) {
                    $q->WhereDate('date',$request->booking_date);
            })
            ->with(['venue'])
            ->latest()->paginate($request->per_page);
        return BookingResource::collection($bookings);
    }
    public function update(Booking $booking,Request $request){
        $booking=tap($booking)->update(['status'=>$request->status]);

        $mail_body='The booking status for the venue '.$booking->venue->name. ' is now '.$booking->status.'.';
        $this->sendEmail($booking->email,$mail_body);
        return new BookingResource($booking);

    }
    public function sendEmail($email,$body)
    {
        if($email){
            $data = array('email' => $email, 'body' => $body);
            try {
                Mail::send('email', $data, function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Booking Status');
                    $message->from(env('MAIL_FROM_ADDRESS'), 'Venue Recommendation');
                });
                return response()->json(['message' => 'Mail Send Successful'], 200);

            } catch (\Exception $e) {
                throw new HttpException(500, $e);
            }
        }
    }
}
