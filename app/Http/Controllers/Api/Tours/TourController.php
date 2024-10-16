<?php

namespace App\Http\Controllers\Api\Tours;

use App\Http\Controllers\ApiController;
use App\Models\Tour;
use App\Models\Tourist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TourController extends ApiController
{
    public function labels(Request $request)
    {
        $tours = Tour::pluck('name', 'id');
        // $tours = Tour::select('id', 'name')->get();

        return response()->json([
            'data' => $tours,
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

        $tours = Tour::when(isset($name), function ($q) use ($name) {
                $q->where('name', 'LIKE', '%' . $name . '%');
            })->when(isset($number), function ($q) use ($number) {
                $q->where('numb er', $number);
            })
            ->paginate(20);

        return response()->json([
            'data' => $tours,
        ]);
    }

    public function show(Tour $tour)
    {
        $tour = $tour->load(['guide', 'driver', 'program']);

        return response()->json([
            'data' => $tour,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'guide_id' => 'required|exists:guides,id',
            'driver_id' => 'required|exists:drivers,id',
            'program_id' => 'required|exists:programs,id',
            'price' => 'required|integer',
            'number' => 'required|integer',
            'date' => 'required|date',
            'name' => 'required|string',
            'status' => 'required',
        ]);

        $tourData = [
            'guide_id' => $request->guide_id,
            'driver_id' => $request->driver_id,
            'program_id' => $request->program_id,
            'price' => $request->price,
            'number' => $request->number,
            'date' => $request->date,
            'name' => $request->name,
            'status' => $request->status,
        ];

        $tour = Tour::create($tourData);

        $tour = $tour->load(['guide', 'driver', 'program']);

        return response()->json([
            'data' => $tour,
        ]);
    }

    public function edit(Tour $tour, Request $request)
    {

        $request->validate([
            'guide_id' => 'exists:guides,id',
            'driver_id' => 'exists:drivers,id',
            'program_id' => 'exists:programs,id',
            'price' => 'integer',
            'number' => 'integer|unique:tours,number,' . $tour->id,
            'date' => 'date',
            'name' => 'string',
            'status' => 'integer|' . Rule::in([1, 2]),
        ]);

        $tourData = [
            'guide_id' => $request->guide_id,
            'driver_id' => $request->driver_id,
            'program_id' => $request->program_id,
            'price' => $request->price,
            'number' => $request->number,
            'date' => $request->date,
            'name' => $request->name,
            'status' => $request->status,
        ];

        $tour->update($tourData);

        $tour = $tour->load(['guide', 'driver', 'program']);
        return response()->json([
            'data' => $tour,
        ]);

    }

    public function addTourist(Tour $tour, Request $request)
    {
        $tourist = Tourist::where('id', $tour->id)->first();

        $tour->load('tourists');

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
        if ($tour->status == Tour::STATUS_OPENED) {
            return $this->showMessage('tour is already opened');
        }

        $tour->status = Tour::STATUS_OPENED;
        $tour->save();
        return $this->showMessage('tour is  opened');
    }

    public function attachTour(Tour $tour, Request $request)
    {
        $tourist = Auth::guard('tourist-api')->user();

        $tourExists = $tourist->tours->contains($tour->id);

        if (!$tourExists) {
            $tourExists = $tourist->tours()->attach($tour->id);
            return $this->showMessage('tour is added');
        } else {
            return $this->showMessage('tour is already added');
        }

    }

}
