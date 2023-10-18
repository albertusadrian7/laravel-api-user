<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function getProfile(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->user()->id;
            $user = User::find($user_id);
            return response()->json(['status' => 'true', 'message' => "Data has been successfully found.", 'user' => $user], 200);
        }
        catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'false', 'message' => 'Failed to found data, please try again', 'errorMessage' => $e->getMessage()], 404);
        }
    }

    public function updateProfile(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->user()->id;
            if ($request->photo) {
                $path = $request->file('photo')->store('public/user_photo');
            }
            $user = User::find($user_id);
            $user->image = $path;
            $user->save();
            DB::commit();
            return response()->json(['status' => 'true', 'message' => 'Data has been successfully updated.'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'false', 'message' => 'Failed to updated data, please try again', 'errorMessage' => $e->getMessage()], 400);
        }
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
