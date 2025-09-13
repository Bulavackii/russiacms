<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * TinyMCE image upload handler
     */
    public function image(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads', 'public');

            return response()->json([
                'location' => asset('storage/' . $path),
            ]);
        }

        return response()->json([
            'error' => 'Файл не загружен',
        ], 422);
    }
}
