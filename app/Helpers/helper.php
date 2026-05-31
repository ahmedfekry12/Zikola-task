<?php

namespace App\Helpers;

class helper
{
    public static function uploadImage($image , $path = 'uploads')
    {
        if ($image) {
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            $image->move(public_path($path), $imageName);

            return $path . '/' . $imageName;
        }

        return null;
    }

    public static function ApiResponse(int $code = 200 , $message = null , $data = null)
    {
        $response = [
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response , $code);
    }
}