<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *    title="Axenso Community API Documentation",
 *    version="1.0.0",
 * )
 */
class ApiController extends Controller
{
    use ApiResponser;

    public function __construct(Request $request, $requireVendor = true)
    {
        //
    }

}
