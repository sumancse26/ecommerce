<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function policyPage()
    {

        return view('pages.policy-page');
    }

    public function policyByType(Request $request)
    {
        try {
            $policy  = Policy::where('type', $request->type)->first();

            return response()->json([
                'success' => true,
                'policy' => $policy
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
