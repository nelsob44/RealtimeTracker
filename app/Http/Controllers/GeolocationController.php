<?php

namespace App\Http\Controllers;
use App\Events\SendLocation;
use Illuminate\Http\Request;

class GeolocationController extends Controller
{
    public function SendLocation(Request $request){
        
        // \Log::info($request->all());
        $lat = $request->input("lat");
        $long = $request->input("long");
        $location = [
            "lat" => $lat, 
            "long" => $long
        ];
    
        event(new SendLocation($location));
    
        return response()->json([
            'status' => 'Success', 
            'data' => $location
        ]);
        
    }
}
