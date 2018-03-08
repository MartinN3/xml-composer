<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Export extends Model
{
    public static function export($data)
    {
        // $xml = new XMLWriter();
        // $xml->openMemory();
        // $xml->startDocument('1.0', 'UTF-8');
        // $xml->startElement("SHOP");
        // $xml->startElement("SHOPITEM");
        // $xml->startElement($data);
        // $xml->endElement();
        // $xml->endElement();
        // $xml->endElement();
        // file_put_contents('../xmlExports/test.xml', $xml->flush(true), FILE_APPEND);
        $xw = xmlwriter_open_memory();
        xmlwriter_set_indent($xw, 1);
        $res = xmlwriter_set_indent_string($xw, ' ');

        xmlwriter_start_document($xw, '1.0', 'UTF-8');

        // A first element
        xmlwriter_start_element($xw, 'SHOP');

        // Start a child element
        foreach ($data as $key => $shopitem) {
        xmlwriter_start_element($xw, 'SHOPITEM');

        xmlwriter_start_attribute($xw, 'id');
        xmlwriter_text($xw, $shopitem->shoptet_id);
        xmlwriter_end_attribute($xw);

        xmlwriter_start_element($xw, 'NAME');
        xmlwriter_text($xw, $shopitem->name);
        xmlwriter_end_element($xw); // NAME

        xmlwriter_start_element($xw, 'SHORT_DESCRIPTION');

        xmlwriter_write_cdata($xw, $shopitem->short_description);
        xmlwriter_end_element($xw); // SHORT_DESCRIPTION

        xmlwriter_end_element($xw); // SHOPITEM
    }
      xmlwriter_end_element($xw); // SHOP

      xmlwriter_end_document($xw);

      Storage::put('test.xml', xmlwriter_output_memory($xw));

      return;   
  }

    public static function exportFromXML()
    {

    $data = DB::table('stock')->select('code', 'ean', 'pictures')->get();

    $codes = []; 
    foreach ($data as $item) {
        $pictures = explode('; ', $item->pictures);
        $codes["$item->code"]['pictures'] = [];

        foreach ($pictures as $picture) {
            array_push($codes["$item->code"]['pictures'], $picture);
        }
    }

    $dom = new \DomDocument();
    //SHOPTET EXPORT URL z nejakeho settingu
    $url = "https://190671.myshoptet.com/export/productsComplete.xml?patternId=-5&hash=d113cdad83e0705e553247dae069608cd782f555042636e6cd13afa7e56b129d";

    $fileDownloaded = file_get_contents($url);
    $name = \Illuminate\Support\Str::random(40);

    \Illuminate\Support\Facades\Storage::put("xml/shoptet/{$name}.xml", $fileDownloaded);
    
    $xml = \Illuminate\Support\Facades\Storage::get("xml/shoptet/{$name}.xml");
    // $xml = Storage::get('xml/shoptet/2FT8KZt6pRFqQ0Y51zrtZ72rW3jm3V3W7iIz6Pf1.xml');
    //FTP route to image by account used
    $imagesAccountUrl = Helper::imagesAccountUrl();
    $dom->loadXML($xml);
    $xpath = new \DOMXPath($dom);

    $roots = $xpath->query('//SHOP');
    if ($roots->length > 0) {
        for ($i = 0; $i < $roots->length; $i++) {
            $products = $xpath->query('./SHOPITEM', $roots->item($i));
            if ($products->length > 0) {
                for ($j = 0; $j < $products->length; $j++) {
                    $node = $products->item($j);
                    $code = $xpath->query('./CODE', $node)->item(0)->nodeValue;
                    if ( isset($codes["$code"]) ) {
                        if ( isset($codes["$code"]['pictures']) && strlen($codes["$code"]['pictures'][0]) > 0 ) {
                            $nodePictures = $codes["$code"]['pictures'];
                            if ( $xpath->evaluate('./IMAGES', $node)->length >= 1 ) {
                                $XML = '';
                                foreach ($nodePictures as $picture) {
                                    $XML .= '<IMAGE description="">' . $imagesAccountUrl . $picture . '</IMAGE>';
                                }
                                $xpath->query('./IMAGES', $node)->item(0)->nodeValue = $XML;
                            } else {
                                // $XML = '<IMAGES>';
                                // foreach ($nodePictures as $picture) {
                                //   $XML .= '<IMAGE description="">' . $picture . '</IMAGE>';
                                // }
                                // $XML .= '</IMAGES>';

                                $xw = xmlwriter_open_memory();
                                xmlwriter_set_indent($xw, 1);

                                xmlwriter_start_element($xw, 'IMAGES');

                                foreach ($nodePictures as $picture) {
                                    xmlwriter_start_element($xw, 'IMAGE');

                                    xmlwriter_start_attribute($xw, 'description');
                                    xmlwriter_text($xw, '');
                                    xmlwriter_end_attribute($xw);

                                    xmlwriter_text($xw, $imagesAccountUrl . $picture);
                                    xmlwriter_end_element($xw);
                                }

                                xmlwriter_end_element($xw);
                                $snippet = xmlwriter_output_memory($xw);
                                $f = $dom->createDocumentFragment();
                                $f->appendXML($snippet);
                                $node->appendChild($f);
                            }
                        }
                    }
                }
            }
        }
    }

    //Zahashovat a ulozit zaznam do databaze, abych pak mohl vracet 
    Storage::put('export.xml', $dom->saveXML());
    }
}
