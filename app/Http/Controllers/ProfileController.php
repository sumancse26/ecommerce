<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profilePage()
    {
        return view('pages.profile-page');
    }
    public function createUpdateProfile(Request $request)
    {
        try {
            $userId = $request->header('userId');
            $request->merge(['user_id' => $userId]);

            $data = CustomerProfile::updateOrCreate(
                ['user_id' => $userId],
                $request->input()
            );

            return response()->json([
                'success' => true,
                'id' => $data->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getProfile(Request $request)
    {
        try {
            $userId = $request->header('userId');
            $data = CustomerProfile::where('user_id', $userId)->first();
            return response()->json([
                'success' => true,
                'userInfo' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
