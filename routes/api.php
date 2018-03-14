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
    $systemName = 'pohoda';
    $uploadType = 'request';

    $importInformations = array(
        'systemName' => $systemName,
        'uploadType' => $uploadType,
        'name' => $name
    );

    $XML = \Illuminate\Support\Facades\Storage::put("xml/{$systemName}/{$name}.xml", $request->getContent());
    
    $response = \Illuminate\Support\Facades\Storage::get("./pohodatest/odpoved/response_export.xml");
    \App\Helper::respondOK($response);
    \App\Helper::logStore($importInformations);

    $import = new \App\Import;
    $import->parsePohodaXML($XML);

    return;
});
