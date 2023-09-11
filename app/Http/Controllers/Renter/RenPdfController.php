<?php

namespace App\Http\Controllers\Renter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Area;
use App\User;
use App\Booking;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class RenPdfController extends Controller
{
    public function generate_pdf($book_id)
    {
        

        $book = Booking::findOrFail($book_id);
    
        $house = Area::findOrFail($book->area_id);
  
        $users = User::where('id', Auth::id())->latest()->get();
        $landlord = User::findOrFail($book->landlord_id);

        
        
        $data = 'Thank you for using our website.';

        $order = Order::where('area_id', $house->id)->where('user_id', Auth::id())->first();

   


        $pdf = Pdf::loadView('renter.booking.billing_invoice',compact('data','house','users','order','book','landlord'));
        return $pdf->stream('renter.booking.billing_invoice');
    }
}
