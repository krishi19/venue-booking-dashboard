<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'contact' => 'required',
            'date' => 'required|date',
            'no_of_people' => 'integer',
            'venue_id' => 'required|exists:venues,id'
        ]);
        Booking::create(
            $request->only('venue_id',
                'full_name',
                'email',
                'contact',
                'event_type',
                'date',
                'location',
                'no_of_people')
        );
        $mail_body='We have received your query about the venue. We will soon follow-up you for further discussion as soon as possible.';
        $this->sendEmail($request->email,$mail_body);
        return response()->json(['message'=>'Booking created and mail sent successfully']);
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
                return response()->json(['message' => 'Mail Send Successfull'], 200);

            } catch (\Exception $e) {
                throw new HttpException(500, $e);
            }
        }
    }
}
