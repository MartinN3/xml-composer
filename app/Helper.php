<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DOMXPath;
use DomDocument;

class Helper extends Model
{
    public static function createXmlDom($xml)
    {
        $dom = new DomDocument();
        $dom->loadXML($xml);
        return new DOMXPath($dom);
    }
}
