<?php

namespace App\Helpers;

/**
 * Developed by Caspian Soft.
 *
 * Description:
 *
 * @author Orkhan Alirzayev
 * @date 12/23/17
 */

class JSON
{
    public static function response($error = null, $message = null, $data = null, $status_code = null)
    {
        return response()->json([
            'error' => $error,
            'message' => $message,
            'data' => $data,
        ], $status_code);
    }

}