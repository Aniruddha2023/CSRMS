<?php

namespace App\Http\Controllers\Renter;

use App\Booking;
use App\Area;
use App\Location;
use App\Http\Controllers\Controller;
use App\Review;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $houses = Area::latest()->get();
        $areas = Location::latest()->get();
        $renters = User::where('role_id', 3)->get();
        $landlords = User::where('role_id', 2)->get();
        return view('renter.dashboard', compact('renters', 'houses', 'areas', 'landlords'));
    }

    public function areas(){
        $areas = Location::latest()->paginate(8);
        $areacount = Location::all()->count();
        return view('renter.area.index', compact('areas', 'areacount'));
    }



    public function allHouses(){
        $houses = Area::latest()->paginate(8);
        $housecount = Area::all()->count();
        return view('renter.house.index', compact('houses', 'housecount'));
    }

    public function housesDetails($id){
        $house = Area::find($id);
        $stayOnceUponATime = Booking::
            where('renter_id', Auth::id())
            ->where('leave', '!=' ,"null")
            ->where('leave', '!=', "Currently Staying")
            ->where('address', $house->address)
            ->first();
            //dd($stayOnceUponATime);
        $alreadyReviewed = Review::where('area_id', $house->id)
                            ->where('user_id', Auth::id())
                            ->first();

        return view('renter.house.show', compact('house', 'stayOnceUponATime', 'alreadyReviewed')); 
    }

    public function review(Request $request){
        $this->validate($request, [
            'opinion' => 'required'
        ]);
        $review = new Review();
        $review->area_id = $request->location_id;
        $review->user_id = Auth::id();
        $review->opinion = $request->opinion;
        $review->save();
        session()->flash('success', 'Review Added Successfully');
        return redirect()->back();
    }

    public function reviewEdit($id){
        $review = Review::find($id);
        return view('renter.review.edit', compact('review'));
    }

    public function reviewUpdate(Request $request,$id){
        $this->validate($request, [
            'opinion' => 'required|min:10'
        ]);
        $review = Review::find($id);
        $review->opinion = $request->opinion;
        $review->save();
        return redirect()->route('renter.houses.details', $review->area_id)->with('success', 'Review Updated Successfully');
    }




    public function bookingHistory(){
        $books = Booking::where('renter_id', Auth::id())->where('booking_status', '!=' , "requested")->get();
        return view('renter.booking.history', compact('books'));
    }

    public function bookingPending(){
        $books = Booking::where('renter_id', Auth::id())->where('booking_status', "requested")->get();
        $houses = Area::latest()->get();
        // $houses = Area::where('id', $books->location_id)->first();
        // dd($books);
        // dd($houses);
        return view('renter.booking.pending', compact('books','houses'));
    }

    // public function bookingPayment(){
        
    //     $house = Area::all();


    //     $location_id = $house->id;

    //     $rent = $house->rent;

    //     return view('renter.exampleHosted', compact('location_id','rent'));
    // }

    public function cancelBookingRequest($id){
        Booking::find($id)->delete();

        session()->flash('success', 'Booking Request Removed Successfully');
        return redirect()->back();
    }
}
