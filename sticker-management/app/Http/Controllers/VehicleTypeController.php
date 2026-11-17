<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVehicleTypeRequest;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['vehicle_types'] = VehicleType::latest()->paginate(30);
        return view('vehicle_type.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vehicle_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateVehicleTypeRequest $request)
    {
        $vehicle_type = new VehicleType();
        $vehicle_type->name = $request->name;
        $vehicle_type->type = $request->type;
        $vehicle_type->created_by = auth()->user()->name;
        $vehicle_type->save();

        return redirect()->route('vehicle-type.index')->with('message', 'Create successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['vehicle_type'] = VehicleType::find($id);
        return view('vehicle_type.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateVehicleTypeRequest $request, $id)
    {
        $vehicle_type = VehicleType::find($id);
        $vehicle_type->name = $request->name;
        $vehicle_type->type = $request->type;
        $vehicle_type->updated_by = auth()->user()->name;
        $vehicle_type->save();
        return redirect()->route('vehicle-type.index')->with('message', 'Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $registrationNumber = VehicleType::findOrFail($id);

        $registrationNumber->delete();

        return redirect()->route('vehicle-type.index')->with('success', 'Vehicle type deleted successfully.');
    }
}
