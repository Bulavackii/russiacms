<?php

namespace Modules\Visual\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    public function fragment(Request $request)
    {
        return response()->json([
            'html' => '<div class="p-4 bg-gray-100">Preview fragment</div>',
        ]);
    }

    public function theme(Request $request)
    {
        return response()->json([
            'css' => ':root { --color-primary: #0ea5e9; }',
        ]);
    }
}
