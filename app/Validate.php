<?php

namespace App;

use App\Export;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Validate extends Model
{
  public function short_description()
  {
     // Get from database short description
     // Apply some filter on it
     $data = DB::table('stock_shoptet')->select('short_description', 'name', 'shoptet_id')->whereRaw('LENGTH(short_description) > 250')->get();
     Export::export($data);

     return;     
  }

  public function test()
  {
    Export::exportFromXML();
    return;
  } 
}
