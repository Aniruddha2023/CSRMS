<?php

namespace App\Http\Controllers;

use App\Area;
use App\User;
use App\Booking;
use App\Contact;
use App\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $houses = Area::where('status', 1)->latest()->paginate(6);
        $locations = Location::all();
        return view('welcome', compact('houses', 'locations'));
    }

    public function highToLow()
    {
        $houses = Area::where('status', 1)->orderBy('rent', 'DESC')->paginate(6);
        $locations = Location::all();
        return view('welcome', compact('houses', 'locations'));
    }

    public function lowToHigh()
    {
        $houses = Area::where('status', 1)->orderBy('rent', 'ASC')->paginate(6);
        $locations = Location::all();
        return view('welcome', compact('houses', 'locations'));
    }

    public function details($id){
        $house = Area::findOrFail($id);
        $user = User::get()->all();
        return view('houseDetails', compact('house','user'));
    }

    public function allHouses(){
        $houses = Area::latest()->where('status', 1)->paginate(12);
        return view('allHouses', compact('houses'));
    }

    public function areaWiseShow($id){
        $area = Location::findOrFail($id);
        $houses = Area::where('location_id', $id)->get();
        return view('areaWiseShow', compact('houses', 'area'));
    }

    public function search(Request $request){
        
        $room = $request->room;
        $rent = $request->rent;
        $address = $request->address;
        $month = $request->month;
        

        if( $room == null  && $rent == null && $month == null && $address == null){
            session()->flash('search', 'Your have to fill up minimum one field for search');
            return redirect()->back();
        }

        $houses = Area::where('rent', 'LIKE', $rent)
            ->where('area_size', 'LIKE',  $room)
            ->where('address', 'LIKE', "%$address%")
            ->where('month', 'LIKE', $month)
            ->get();
        return view('search', compact('houses'));
    }

    public function searchByRange(Request $request){
        $digit1 =  $request->digit1;
        $digit2 =  $request->digit2;
        if($digit1 > $digit2){
            $temp = $digit1;
            $digit1 =  $digit2;
            $digit2 = $temp;
        }
        $houses = Area::whereBetween('rent', [$digit1, $digit2])
                        ->orderBy('rent', 'ASC')->get();
        return view('searchByRange', compact('houses'));
    }


    public function booking($house){
        

        $house = Area::findOrFail($house);
        $landlord = User::where('id', $house->user_id)->first();

        // if(Booking::where('address', $house->address)->where('booking_status', "booked")->count() > 0){
        //     session()->flash('danger', 'This house has already been booked!');
        //     return redirect()->back();
        // }

        if(Booking::where('area_id', $house->id)->where('booking_status', "booked")->count() > 0){
            session()->flash('danger', 'This house has already been booked!');
            return redirect()->back();
        }



        // if(Booking::where('address', $house->address)->where('renter_id', Auth::id())->where('booking_status', "requested")->count() > 0){
        //     session()->flash('danger', 'Your have already sent booking request of this home');
        //     return redirect()->back();
        // }

        if(Booking::where('area_id', $house->id)->where('renter_id', Auth::id())->where('booking_status', "requested")->count() > 0){
            session()->flash('danger', 'Your have already sent booking request of this home');
            return redirect()->back();
        }
        
        
        $booking = new Booking();
        $booking->area_id = $house->id;
        $booking->address = $house->address;
        $booking->rent = $house->rent;
        $booking->landlord_id = $landlord->id;
        $booking->renter_id = Auth::id();
        $booking->save();


        session()->flash('success', 'Area Booking Request Send Successfully');
        return redirect()->back();
 

    }

    public function bookingPayment($house){

        $house = Area::findOrFail($house);
        $landlord = User::where('id', $house->user_id)->first();

        // if(Booking::where('address', $house->address)->where('booking_status', "booked")->count() > 0){
        //     session()->flash('danger', 'This house has already been booked!');
        //     return redirect()->back();
        // }

        if(Booking::where('area_id', $house->id)->where('booking_status', "booked")->count() > 0){
            session()->flash('danger', 'This house has already been booked!');
            return redirect()->back();
        }



        // if(Booking::where('address', $house->address)->where('renter_id', Auth::id())->where('booking_status', "requested")->count() > 0){
        //     session()->flash('danger', 'Your have already sent booking request of this home');
        //     return redirect()->back();
        // }

        if(Booking::where('area_id', $house->id)->where('renter_id', Auth::id())->where('booking_status', "requested")->count() > 0){
            session()->flash('danger', 'Your have already sent booking request of this home');
            return redirect()->back();
        }
        
        
        $booking = new Booking();
        $booking->area_id = $house->id;
        $booking->address = $house->address;
        $booking->rent = $house->rent;
        $booking->landlord_id = $landlord->id;
        $booking->renter_id = Auth::id();
        $booking->save();

        $location_id = $house->id;
        $rent = $house->rent;
        // $renter_id = $booking->renter_id;

        $user = Auth::user();

        return view('renter.exampleHosted', compact('user','location_id','rent'));


        // session()->flash('success', 'Area Booking Request Send Successfully');
        // return redirect()->back();
 

    }

    public function contact(){
        return view('contact');
    }

    public function storeContact(Request $request){
        $request->validate([
            'name'=> 'required',
            'email' => 'required',
            'phone' => 'required|min:11',
            'message' => 'required|min:20'
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Message Sent Successfully!');
    }


}
