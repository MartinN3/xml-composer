<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

        //Pouze pro DEBUG LIDMI
        // xmlwriter_start_attribute($xw, 'pocetZnaku');
        // xmlwriter_text($xw, mb_strlen($shopitem->short_description, 'UTF-8'));
        // xmlwriter_end_attribute($xw);

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
      $dom = new \DOMDocument();
      $dom->preserveWhiteSpace = false; 
      $dom->load( base_path() . './storage/app/xml/shoptet/0fsWCSlaP9OZDlEh3u7C7FHrAyED0VNXsI2t1kpZ.xml');

      $data = [
        'codes' => [ 'DT204', 'DT195'],
        'XML' => $dom
      ];

      $exportXML = $data['XML'];

      $rootnode = $exportXML->getElementsByTagName("SHOP")->item(0);

      $nodes = $exportXML->getElementsByTagName("SHOPITEM");

      $domElemsToRemove = array();

      foreach ($nodes as $key => $node) {
        if ($node->hasChildNodes()) {
          $code = $node->getElementsByTagName("CODE")->item(0)->nodeValue;

          if ( !in_array( $code, $data['codes']) ) {
            $domElemsToRemove[] = $node;
          }
        }
      }

      foreach( $domElemsToRemove as $domElement ){ 
        $domElement->parentNode->removeChild($domElement); 
      } 
      
      Storage::put('export.xml', $exportXML->saveXML());
    }
}
