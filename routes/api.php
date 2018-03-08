<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:api')->get('/test', function (Request $request) {
//     return ['name' => 'Test'];
// });

Route::post('/test', function (Request $request) {
    $name = \Illuminate\Support\Str::random(40);

    \Illuminate\Support\Facades\Storage::put("./pohodatest/ulozene/{$name}.xml", $request->getContent());
    
    $XML = \Illuminate\Support\Facades\Storage::get("./pohodatest/odpoved/response_export.xml");

    $import = new \App\Import;
    $import->parsePohodaXML($XML);
    \App\Export::exportFromXML();
    
    return $XML;
});
