<?php

namespace App\Http\Controllers;

use App\Models\locationsModel;
use Illuminate\Http\Request;

class locationsController extends Controller
{
    public function index()
    {
        $locations = locationsModel::all();
        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'geodata' => 'required|string',
            ]);
            $coordinates = explode(',', $validatedData['geodata']);
            if (count($coordinates) !== 2) {
                return redirect()->back()->with('error', 'Invalid coordinates format. Please use "latitude,longitude".');
            }

            $latitude = trim($coordinates[0]);
            $longitude = trim($coordinates[1]);

            $location = new locationsModel();
            $location->name = $validatedData['name'];
            $location->latitude = $latitude;
            $location->longitude = $longitude;
            $location->save();

            return redirect()->route('locations.index')->with('success', 'Location created successfully.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'An error occurred while creating the location.');
        }
    }

    public function show($id)
    {
    }

    public function edit(locationsModel $location)
    {
        $location->geodata = $location->latitude . ',' . $location->longitude;
        return view('locations.edit', compact('location'));
    }


    public function update(Request $request, locationsModel $location)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'geodata' => 'nullable|string',
            ]);

            $coordinates = explode(',', $request->geodata);
            if (count($coordinates) !== 2) {
                return redirect()->back()->with('error', 'Invalid coordinates format. Please use "latitude,longitude".');
            }

            $latitude = trim($coordinates[0]);
            $longitude = trim($coordinates[1]);

            $location->update([
                'name' => $request->name,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);

            return redirect()->route('locations.edit', $location)->with('success', 'Location updated successfully');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'An error occurred while creating the location.');
        }
    }
    public function destroy(locationsModel $location)
    {
        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Location deleted successfully');
    }
}
