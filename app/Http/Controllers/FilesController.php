<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;

class FilesController extends Controller
{

    public function show($filename)
    {
        $path = storage_path('app\\advertisement\\' . $filename);
        if (!File::exists($path)) {
            abort(404);
        }
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function store(Request $request)
    {
        $path = $request->file('advertisement_photo')->store('advertisement');
        return $path;
    }
}
