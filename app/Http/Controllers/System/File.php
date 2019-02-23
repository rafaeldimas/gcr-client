<?php

namespace Gcr\Http\Controllers\System;

use Illuminate\Http\Request;
use Gcr\Http\Controllers\Controller;
use Illuminate\Support\Facades\File as SupportFile;

class File extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $fileName
     * @return void
     */
    public function __invoke(Request $request, $fileName)
    {
        $pathName = storage_path("app/documents/{$fileName}");
        if (!SupportFile::exists($pathName)) {
            abort(404);
        }
        return response()->file($pathName);
    }
}
