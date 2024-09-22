<?php

namespace App\Http\Controllers\Api\Programs;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProgramController extends ApiController
{
    public function index(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $programs = Program::when(isset($to_date), function($q) use ($from_date, $to_date){
                    $q->whereBetween('created_at', [$from_date, $to_date]);
                })
                ->when(!isset($to_date), function($q) use ($from_date){
                        $q->where('created_at', $from_date);
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
        $validator = Validator::make($request->all(), [
            'type' => [
                'string',
                'required',
                Rule::in(['free', 'platinum', 'premium'])],
            'name' => 'required|string',
            'description' => 'required|string',
        ]);
        $programData = $validator->validated();

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
