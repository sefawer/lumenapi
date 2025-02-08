<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Location;

class LocationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function create(Request $request)
    {
        //Create Location Info
        return Location::create([
            "name"=>$request->input('name'),
            "latitude"=>$request->input('latitude'),
            "longitude"=>$request->input('longitude'),
            "marker_color"=>$request->input('marker_color')
        ])->toJson();
    }

    public function list()
    {
        return Location::all()->toJson();
    }

    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        $location->update($request->all());
        return response()->json(['message' => 'Location updated successfully']);
    }

    public function info($id) {
        //Detail of a location
    }

    public function view()
    {
        // Örnek birden fazla konum verisi
        /*$locations = [
            ['latitude' => 40.7128, 'longitude' => -74.0060, 'name' => 'New York', 'markerColor' => 'red'],
            ['latitude' => 34.0522, 'longitude' => -118.2437, 'name' => 'Los Angeles', 'markerColor' => 'blue'],
            ['latitude' => 51.5074, 'longitude' => -0.1278, 'name' => 'London', 'markerColor' => 'green'],
        ];*/

        $locations = Location::all();
        // Veriyi blade'e gönderiyoruz
        return View::make('location', [
            'locations' => $locations
        ]);
    }

    public function delete ($id)
    {
        $location = Location::find($id);

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Location deleted successfully'
        ]);
    }
}
