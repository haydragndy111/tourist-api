<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GuideController extends Controller
{
    public function index(Request $request)
    {
        $guides = Guide::paginate(20);

        return response()->json([
            'data' => $guides,
        ]);
    }

    public function show(Guide $guide)
    {
        $guide = $guide->load(['tours']);

        return response()->json([
            'data' => $guide,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'f_name' => 'string|required',
            'l_name' => 'string|required',
            'address' => 'string|required',
            'mobile' => 'string|required',
            'description' => 'string|required',
        ]);

        $guideData = [
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'description' => $request->description,
        ];

        $guide = Guide::create($guideData);

        $guide = $guide->load(['tours']);

        return response()->json([
            'data' => $guide,
        ]);
    }

    public function edit(Guide $guide, Request $request)
    {

        $request->validate([
            'f_name' => 'string',
            'l_name' => 'string',
            'address' => 'string',
            'mobile' => 'string',
            'description' => 'string',
        ]);

        $guideData = [
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'description' => $request->description,
        ];

        $guide->update($guideData);

        $guide = $guide->load(['tours']);

        return response()->json([
            'data' => $guide,
        ]);

    }

}
