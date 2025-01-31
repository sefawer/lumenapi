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
    public function create()
    {
        //Create Location Info
    }

    public function list()
    {
        //Get list of Locations
    }

    public function update($id)
    {
        //Update Location Info
    }

    public function info($id) {
        //Detail of a location
    }

    public function view()
    {
        // Örnek birden fazla konum verisi
        $locations = [
            ['latitude' => 40.7128, 'longitude' => -74.0060, 'name' => 'New York', 'markerColor' => 'red'],
            ['latitude' => 34.0522, 'longitude' => -118.2437, 'name' => 'Los Angeles', 'markerColor' => 'blue'],
            ['latitude' => 51.5074, 'longitude' => -0.1278, 'name' => 'London', 'markerColor' => 'green'],
        ];

        $locations = Location::all();
        // Veriyi blade'e gönderiyoruz
        return View::make('location', [
            'locations' => $locations
        ]);
    }
}
