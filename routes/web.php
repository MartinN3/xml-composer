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

Route::post('/import', 'ImportController@store');

Route::get('/pohoda/url/{default?}', function ($default   = null) {
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

Route::get('/shoptet/url/{default?}', function ($default = null)
{
	$systemName = 'shoptet';
	if ($default === 'default') {
		$url = "https://190671.myshoptet.com/export/productsComplete.xml?patternId=-5&hash=d113cdad83e0705e553247dae069608cd782f555042636e6cd13afa7e56b129d";

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
