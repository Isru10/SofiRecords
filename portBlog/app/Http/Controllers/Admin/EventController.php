<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events=Event::all();
        return response()->json([
            'success' => true,
            'events' => $events,
        ]);
        // return DB::table('events')->latest('created_at')->first();

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            // 
            'image' => 'nullable',
            'start_date'=>'required',
            'end_date'=>'required',
            'location'=>'required',
            'price_and_location'=>'required',
            'phone'=>'required' ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
            } else {
                $imagePath = null;
            }

            $event = Event::create([
                'title' => $request->title,
                'body' => $request->body,
                'image_path' => $imagePath,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
                'location'=>$request->location,
                'price_and_location'=>$request->price_and_location,
                'phone'=>$request->phone    ]);
    
                return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $event = Event::findorfail($id);
        return $event;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $event=Event::find($id);
        if($event){
            return response()->json(["events"=>$event,"status"=>200],200);
        }
        else{
            return response()->json(["message"=>"no such event!","status"=>404],404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'start_date'=>'required',
            'end_date'=>'required',
            'location'=>'required',
            'price_and_location'=>'required',
            'phone'=>'required' ]);
        $event = Event::find($id);
        if ($request->hasFile('image')) {
            // Delete the old image
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
            $imagePath = $request->file('image')->store('images', 'public');
        } else {
            $imagePath = $event->image_path;
        }

        $event->update([
            'title' => $request->title,
            'body' => $request->body,
            'image_path' => $imagePath,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'location'=>$request->location,
            'price_and_location'=>$request->price_and_location,
            'phone'=>$request->phone,
        ]);

        return response()->json($event);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }
        $event->delete();

        return response()->json("deleted event", 204);

    }


    public function recent(){
        $event = Event::orderBy('created_at', 'desc')->take(2)->get();
        
        return response()->json(["events"=>$event]);

    }


    public function numevent(){
        $event=Event::count();
        return response()->json([
            'success' => true,
            'event' =>$event,
        ]);
}


        public function someevents(){
            $events = Event::latest()->take(2)->get();
            return response()->json([
                "events"=>$events,
                "sucess"=>true
            ]);
        }
}
