<?php

namespace App\Http\Controllers\Api\Drivers;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function labels(Request $request)
    {
        $drivers = Driver::pluck('name', 'id');
        // $drivers = Driver::select('id', 'name')->get();

        return response()->json([
            'data' => $drivers,
        ]);
    }

    public function index(Request $request)
    {
        $request->validate([
            'number' => 'integer',
            'name' => 'string',
        ]);

        $number = $request->number;
        $name = $request->name;

        $drivers = Driver::paginate(20);

        return response()->json([
            'data' => $drivers,
        ]);
    }

    public function show(Driver $driver)
    {
        $driver = $driver->load(['tours']);

        return response()->json([
            'data' => $driver,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|string',
            'l_name' => 'required|string',
            'address' => 'required|string',
            'plate_number' => 'required|string|unique:drivers',
            'description' => 'required|string',
        ]);

        $driverData = $validator->validated();

        $driver = Driver::create($driverData);

        $driver = $driver->load(['tours']);

        return response()->json([
            'data' => $driver,
        ]);
    }

    public function edit(Driver $driver, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'f_name' => 'string',
            'l_name' => 'string',
            'address' => 'string',
            'plate_number' => 'string|unique:drivers,plate_number,'. $driver->id,
            'description' => 'string',
        ]);

        $driverData = $validator->validated();

        $driver->update($driverData);

        $driver = $driver->load(['tours']);
        return response()->json([
            'data' => $driver,
        ]);

    }

    public function report(Driver $driver, Request $request)
    {
        $request->validate([
            'from_date' => 'date|sometimes',
            'to_date' => 'date|sometimes',
        ]);

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $tours = $driver->tours()->when(isset($from_date), function($q) use ($from_date){
                        $q->whereDate('date', '>=', $from_date);
                    })->when(isset($to_date), function($q) use ($to_date){
                        $q->whereDate('date', '<=', $to_date);
                    })
                ->paginate(20);
        $count = count($tours);

        return response()->json([
            'tours' => $count,
            'data' => $tours,
        ]);
    }

}
