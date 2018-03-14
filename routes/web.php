<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/validate/show', 'ValidateController@show');

Route::post('/import', 'ImportController@store');

Route::post('/backup', 'ImportController@backup');

Route::get('/pohoda/url/{default?}', function ($default = null) {
    $systemName = 'pohoda';
    if ($default === 'default') {
		$url = "https://www.stormware.cz/xml/samples/version_2/export/Zasoby/Response/zasoby_01_v2.0.xml";
        $fileDownloaded = file_get_contents($url);
        $name = \Illuminate\Support\Str::random(40);

        \Illuminate\Support\Facades\Storage::put("xml/{$systemName}/{$name}.xml", $fileDownloaded);
        
        $XML = \Illuminate\Support\Facades\Storage::get("xml/{$systemName}/{$name}.xml");
        $import = new App\Import();
        $import->parsePohodaXml($XML);
	}

    return view('pohoda-url');
});

Route::get('/pohoda/file', function () {
    return view('pohoda-file');
});

Route::get('/shoptet', function () {
    return view('shoptet');
});

Route::get('/shoptet/url/{default?}', function ($default = null)
{
	$systemName = 'shoptet';
	if ($default === 'default') {
		$url = "https://163138.myshoptet.com/export/productsComplete.xml?patternId=-5&hash=05a58660e838a411fd6e37f55db56bb14a9e7c279715cbee4d22d7fda1bcc043";

        $fileDownloaded = file_get_contents($url);
        $name = \Illuminate\Support\Str::random(40);

        \Illuminate\Support\Facades\Storage::put("xml/{$systemName}/{$name}.xml", $fileDownloaded);
        
        $XML = \Illuminate\Support\Facades\Storage::get("xml/{$systemName}/{$name}.xml");
        $import = new App\Import();
        $dom = $import->parseShoptetXml($XML);
	} 

	return view('shoptet-url');
});

Route::get('/shoptet/file', function () {
    return view('shoptet-file');
});
