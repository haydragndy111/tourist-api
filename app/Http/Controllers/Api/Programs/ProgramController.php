<?php

namespace App\Http\Controllers\Api\Programs;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProgramController extends ApiController
{
    public function index(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        // $programs = Program::when(isset($from_date), function ($q) use ($name) {
        //         $q->where('name', 'LIKE', '%' . $name . '%');
        //     })->when(isset($number), function ($q) use ($number) {
        //         $q->where('created_at', $from_date);
        //     })
        //     ->paginate(20);

        $programs = Program::when(isset($to_date), function($q) use ($to_date){
                    // $to_date = Carbon::createFromFormat('d/m/Y',$to_date);
                    // dd("das");
                    $q->whereDate('created_at', '<=', $to_date);
                })
                ->when(isset($from_date), function($q) use ($from_date){
                        // $from_date = Carbon::createFromFormat('d/m/Y',$from_date);
                        $q->whereDate('created_at', '>=', $from_date);
                    })
                ->paginate(10);

        return response()->json([
            'data' => $programs,
        ]);
    }

    public function show(Program $program)
    {
        $program->load('tours');
        return response()->json([
            'data' => $program,
        ]);
    }

    public function create(Request $request)
    {
        $validator = $request->validate([
            'type' => [
                'string',
                'required',
                Rule::in(['free', 'platinum', 'premium'])],
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $programData = [
            'type' => $request->type,
            'name' => $request->name,
            'description' => $request->description,
        ];

        $program = Program::create($programData);

        return response()->json([
            'data' => $program,
        ]);
    }

    public function edit(Program $program, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => [
                'string',
                Rule::in(['free', 'platinum', 'premium'])],
            'name' => 'string',
            'description' => 'string',
        ]);

        $programData = $validator->validated();

        $program->update($programData);

        return response()->json([
            'data' => $program,
        ]);

    }

}
