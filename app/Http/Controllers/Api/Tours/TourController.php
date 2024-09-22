<?php

namespace App\Http\Controllers\Api\Tours;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Tourist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TourController extends ApiController
{
    public function index(Request $request)
    {
        $request->validate([
            'number' => 'integer',
            'name' => 'string',
        ]);
        $number = $request->number;
        $name = $request->name;

        $tours = Tour::when(isset($name), function($q) use ($name){
                    $q->where('name', 'LIKE', '%'.$name.'%');
                })->when(isset($number), function($q) use ($number){
                    $q->where('number', $number);
                })
            ->paginate(20);

        return response()->json([
            'data' => $tours,
        ]);
    }

    public function show(Tour $tour)
    {
        $tour = $tour->load(['guide','driver','program']);

        return response()->json([
            'data' => $tour,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guide_id' => 'required|exists:guides,id',
            'driver_id' => 'required|exists:drivers,id',
            'program_id' => 'required|exists:programs,id',
            'price' => 'required|integer',
            'number' => 'required|integer',
        ]);

        $tourData = $validator->validated();

        $tour = Tour::create($tourData);

        $tour = $tour->load(['guide','driver','program']);

        return response()->json([
            'data' => $tour,
        ]);
    }

    public function edit(Tour $tour, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'guide_id' => 'exists:guides,id',
            'driver_id' => 'exists:drivers,id',
            'program_id' => 'exists:programs,id',
            'price' => 'integer',
            'number' => 'integer',
        ]);

        $tourData = $validator->validated();

        $tour->update($tourData);

        $tour = $tour->load(['guide','driver','program']);
        return response()->json([
            'data' => $tour,
        ]);

    }

    public function addTourist(Tour $tour, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tourist_id' => 'exists:tourists,id',
        ]);

        $requestData = $validator->validated();
        $tourist = Tourist::where('id', $requestData['tourist_id'])->first();

        $tour->load('tourists');
        $tourists = $tour->tourists;

        if ($tourist->tour_id == $tour->id) {
            return $this->showMessage('tourist already registered');
        }

        $tourist->update([
            'tour_id' => $tour->id,
        ]);

        return $this->showMessage('tourist registered');
    }

    public function openTour(Tour $tour, Request $request)
    {
        if($tour->status == Tour::STATUS_OPENED){
            return $this->showMessage('tour is already opened');
        }

        $tour->status = Tour::STATUS_OPENED;
        $tour->save();
        return $this->showMessage('tour is  opened');
    }

}
