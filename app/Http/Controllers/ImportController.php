<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Import;
use Illuminate\Http;
use Illuminate\Http\FileHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function backup(Request $request)
    {
        $systemName = request('systemName');

        if ( null === $systemName ) {
            die('Unspecified `systemName` in request');
        }

        Import::backupImages($systemName);

        return back();
    }
    public function store(Request $request)
    {
        $import = new Import();
        $uploadType = request('uploadType');
        $systemName = request('systemName');

        if ( null === $uploadType ) {
            die('Unspecified `uploadType` in request');
        }

        if ( null === $systemName ) {
            die('Unspecified `systemName` in request');
        }

        switch ($uploadType) {
            case 'url':
                $urlRequested = request('url');
                if (  null === $urlRequested ) {  
                    die('`url` is empty, probably not specified in input');
                }
                $fileDownloaded = file_get_contents($urlRequested);
                $name = Str::random(40);

                Storage::put("xml/{$systemName}/{$name}.xml", $fileDownloaded);
                
                $XML = Storage::get("xml/{$systemName}/{$name}.xml");
                break;

            case 'file':
                $fileUploaded = request()->file('xml');
                if (  null === $fileUploaded ) {  
                    die('`xml` is empty, probably forgot to choose XML to upload');
                }
                $name = $fileUploaded->hashName();
                $XML = $fileUploaded->storeAs("xml/{$systemName}", $name);
                $XML = Storage::get($XML);
                break;

            default:
                die('This `uploadType` request has no case specified');
                break;
        }

        $importInformations = array(
            'systemName' => $systemName,
            'uploadType' => $uploadType,
            'name' => $name
        );

        Helper::logStore($importInformations);

        switch ($systemName) {
            case 'pohoda':
                $import->parsePohodaXml($XML);
                break;
            case 'shoptet':
                $import->parseShoptetXml($XML);
                break;
            default:
                die('This `systemName` request has no case specified');
                break;
        }

        return back();
    }

    public function pohoda(Request $request)
    {
        var_dump($request->all());die;
    }
}
