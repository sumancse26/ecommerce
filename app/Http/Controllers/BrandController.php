<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function brandList(Request $req)
    {
        try {
            $brandList = Brand::select('id', 'brandName', 'brandImg')->get();
            return response()->json([
                'success' => true,
                'brandList' => $brandList
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
