<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Helper extends Model
{
    public static function createXmlDom($xml)
    {
        $dom = new \DomDocument();
        $dom->preserveWhiteSpace = false;
        // $xmlDom = Storage::get($xml);
        $dom->loadXML($xml);
        return new \DOMXPath($dom);
    }

    public static function logStore($args)
    {
        $defaultTimestamps = [
            'created_at' => new Carbon(),
            'updated_at' => new Carbon()
        ];

        DB::table('imports_log')->insert( array_merge($defaultTimestamps, $args) );

        return;
    }

    public static function imagesAccountUrl()
    {
      //get user account
      //get url to account storage folder
      //return 
      $accountStorageUrl = Storage::url('public/martin/');
      return $accountStorageUrl;
    }

    public static function respondOK($text = null)
    {
      // check if fastcgi_finish_request is callable
          if (is_callable('fastcgi_finish_request')) {
              if ($text !== null) {
                  echo $text;
              }
              /*
               * http://stackoverflow.com/a/38918192
               * This works in Nginx but the next approach not
               */
              session_write_close();
              fastcgi_finish_request();
       
              return;
          }
       
          ignore_user_abort(true);
       
          ob_start();
       
          if ($text !== null) {
              echo $text;
          }
       
          $serverProtocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL', FILTER_SANITIZE_STRING);
          header($serverProtocol . ' 200 OK');
          // Disable compression (in case content length is compressed).
          header('Content-Encoding: none');
          header('Content-Length: ' . ob_get_length());
       
          // Close the connection.
          header('Connection: close');
       
          ob_end_flush();
          ob_flush();
          flush();
    }
}
