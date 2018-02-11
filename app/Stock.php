<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public function insert_item($data)
    {
    	static::create([
    		'section_id': $data['section_id']},
    		'page_id': {$data['page_id']},
    		'active': 1,
    		'title': {$data['title']},
    		'sku': {$data['sku']},
    		'ean': {$data['ean']},
    		'stock': {$data['stock']},
    		'price': {$data['price']},
    		'unit': {$data['unit']},
    		'weight': {$data['weight']},
    		'description': {$data['description']},
    		'full_desc': {$data['description']},
    		'pohoda_id': {$data['item_id']}
    	]);
    }
}
