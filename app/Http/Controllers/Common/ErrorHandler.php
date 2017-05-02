<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ErrorHandler extends Controller
{
    public static function noConnectElastic($exception = null) {
        if ($exception) {
            \Log::debug($exception);
        }
        \Session::flash('toastr', [
            [
                'level' => 'error',
                'title' => 'Lỗi kết nối',
                'message' => 'Không thể kết nối tới elastic search',
            ]
        ]);
    }
}
