<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Helper extends Model
{
    public static function createXmlDom($xml)
    {
        $dom = new \DomDocument();
        $dom->preserveWhiteSpace = false;
        $xml = Storage::get($xml);
        $dom->loadXML($xml);
        return new \DOMXPath($dom);
    }

    public static function imagesAccountUrl()
    {
      //get user account
      //get url to account storage folder
      //return
      $accountStorageUrl = 'test';
      return $accountStorageUrl;
    }
}
