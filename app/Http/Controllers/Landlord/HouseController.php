<?php

namespace App\Http\Controllers\Landlord;

use App\Area;
use DB;
use App\Location;
use App\Order;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $houses = Area::latest()->where('user_id', Auth::id())->paginate(8);
        $housecount = Area::all()->count();
        $order = Order::all()->where('user_id', Auth::id());
        return view('landlord.house.index', compact('houses', 'housecount','order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Location::count() < 1){
            session()->flash('danger','To add new house you have to add area first');
            return redirect()->back();
        }
        
        $areas = Location::all();
        return view('landlord.house.create', compact('areas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'address' => 'required',
            'location_id' => 'required',
            'area_size' => 'required|numeric|integer',
            'description' => 'required',
            'month' => 'required',
            'rent' => 'required|numeric',
            'featured_image' => 'required|mimes:jpeg,png,jpg',
            'images.*' => 'required|mimes:jpeg,png,jpg',
            // 'video' => 'required',
            // 'video' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
        ]);

        //handle featured image
        $featured_image = $request->file('featured_image');
        if($featured_image)
        {
             // Make Unique Name for Image 
            $currentDate = Carbon::now()->toDateString();
            $featured_image_name = $currentDate.'-'.uniqid().'.'.$featured_image->getClientOriginalExtension();
  
  
          // Check Dir is exists
  
              if (!Storage::disk('public')->exists('featured_house')) {
                 Storage::disk('public')->makeDirectory('featured_house');
              }
  
  
              // Resize Image  and upload
              $cropImage = Image::make($featured_image)->resize(400,300)->stream();
              Storage::disk('public')->put('featured_house/'.$featured_image_name,$cropImage);
  
         }
       


        if($request->hasfile('images'))
        {
             foreach($request->file('images') as $file)
             {
                 $name = time() . '-'. uniqid() . '.'.$file->extension();
                 $file->move(public_path().'/images/', $name);  
                 $data[] = $name;  
             }
        }

        //handle video

        

        // $video = $request->file('video');

        // if($request->hasfile('video'))
        // {
        //      foreach($request->file('video') as $file)
        //      {
        //          $v_name = time() . '-'. uniqid() . '.'.$file->extension();
        //          $file->move(public_path().'/videos/', $v_name);  
        //          $video = $v_name;  
        //      }
        // }

    //     $file=$request->file('video');
    //    $file->move('videos',$file->getClientOriginalName());
    //    $file_name=$file->getClientOriginalName();

    //    $insert=new video();
    //    $insert->video = $file_name;
    //    $insert->save();


    
        $house = new Area();
        $house->plot_code = 'HC-'.mt_rand(100000,999999);
        $house->address = $request->address;
        $house->user_id = Auth::id();
        $house->contact = Auth::user()->contact;
        $house->location_id = $request->location_id;
        $house->area_size = $request->area_size;
        $house->description = $request->description;
        $house->month = $request->month;
        $house->rent = $request->rent;
        $house->images = json_encode($data);
        $house->featured_image = $featured_image_name;
        // $house->video = $file_name; 
        $house->map_link = $request->map_link;



        $house->save();
        // return redirect(route('landlord.house.index'))->with('success', 'Area Added successfully');


        // =============For Payment start ===================

        // $location_id = DB::table('houses')->insertGetId($house);

        $location_id = $house->id;

        // Mail::to($email)->send(new invoiceMail($data));     //-------For sending Mail to user---------

        $user = Auth::user();
        $rent = $request->rent;

        //session()->flash('type','success');
        //session()->flash('message','Successfully Property Inserted');

        $house_rent = $request->rent;

        $house_add_fee = (($house_rent * 10)/100);

        if($house_add_fee > 1000){
            $price = '1000';
        }else{
            $price = $house_add_fee;
        }

        return view('landlord.exampleHosted', compact('user','location_id','rent','price'));

        // return view('landlord.exampleHosted', compact('user','location_id','rent'));

        // =============For Payment end ===================
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Area $house)
    {
        return view('landlord.house.show')->with('house', $house); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $house)
    {
        $areas = Location::all();
        return view('landlord.house.edit', compact('areas', 'house'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function switch($id)
    {
        $house = Area::find($id);

        if($house->status == 1){
            $house->status = 0;
        }else{
            $house->status = 1;
        }
        $house->save();

        session()->flash('success', 'Area Status Changed Successfully');
        return redirect()->back();
    }

    // ===========for admin approval========

    public function pendingHouse(){
        $houses = Area::where('a_status', 0)->paginate(8);
        
        return view('landlord.house.pending', compact('houses'));
    }

    // ===========for admin approval========

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $house)
    {
        
        
        $this->validate($request,[
            'address' => 'required',
            'location_id' => 'required',
            'area_size' => 'required|numeric|integer',
            'description' => 'required',
            'month' => 'required',
            'rent' => 'required|numeric',
            'featured_image' => 'mimes:jpeg,png,jpg',
            'images.*' => 'mimes:jpeg,png,jpg',
        ]);

        //handle featured image

        $featured_image = $request->file('featured_image');

        if($featured_image)
        {
     
             // Make Unique Name for Image 
            $currentDate = Carbon::now()->toDateString();
            $featured_image_name =$currentDate.'-'.uniqid().'.'.$featured_image->getClientOriginalExtension();
  
  
             // Check Dir is exists
              if (!Storage::disk('public')->exists('featured_house')) {
                 Storage::disk('public')->makeDirectory('featured_house');
              }

              
              // Resize Image and upload
              $cropImage = Image::make($featured_image)->resize(400,300)->stream();
              Storage::disk('public')->put('featured_house/'.$featured_image_name,$cropImage);

              if(Storage::disk('public')->exists('featured_house/'.$house->featured_image)){
                 Storage::disk('public')->delete('featured_house/'.$house->featured_image);
             }
             $house->featured_image = $featured_image_name;
         }

       
        //handle multiple images update
        if($request->hasfile('images'))
        {
           
             foreach(json_decode($house->images) as $picture){
                     @unlink("images/". $picture);
             }
         
             foreach($request->file('images') as $file)
             {
                 $name = time() . '-'. uniqid() . '.'.$file->extension();
                 $file->move(public_path().'/images/', $name);  
                 $data[] = $name;  
             }

             $house->images=json_encode($data);
        }

        $house->address = $request->address;
        $house->location_id = $request->location_id;
        $house->area_size = $request->area_size;
        $house->description = $request->description;
        $house->month = $request->month;
        $house->rent = $request->rent;
        $house->save();
        return redirect(route('landlord.house.pending'))->with('success', 'Area Updated successfully');



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $house)
    {
        //delete multiple added images
        foreach(json_decode($house->images) as $picture){
            @unlink("images/". $picture);
        }

        //delete old featured image
        if(Storage::disk('public')->exists('featured_house/'.$house->featured_image)){
            Storage::disk('public')->delete('featured_house/'.$house->featured_image);
        }

        $house->delete();
        return redirect(route('landlord.house.index'))->with('success', 'Area Removed Successfully');
    }


    // ==============for pdf========================
        // public function viewinvoice(int $orderid){
        //     $order = Order::FindOrFail($orderid);
        //     return view('landlord.invoice.invoice');
        // }
        public function viewinvoice(int $location_id){
            
            $order = Order::where('area_id',$location_id)->first();
         
            return view('landlord.invoice.invoice');
        }

    // ==============for pdf========================
}






