<?php

namespace App;

use App\Helper;
use Carbon\Carbon;
use Config;
use DB;
use DOMXPath;
use DomDocument;
use FilesystemIterator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Import extends Model
{
    private $carbon;

    public function __construct()
    {
        $this->carbon = new Carbon();
    }

    public function logStore($args)
    {
        $defaultTimestamps = [
            'created_at' => $this->carbon,
            'updated_at' => $this->carbon
        ];

        DB::table('imports_log')->insert( array_merge($defaultTimestamps, $args) );

        return;
    }

    public function parseShoptetXml($xml)
    {
        $args = [];
        $timestamp = $this->carbon;
        $xpath = Helper::createXmlDom($xml);
        $roots = $xpath->query('//SHOP');
        if ($roots->length > 0) {
            for ($i = 0; $i < $roots->length; $i++) {
                $products = $xpath->query('./SHOPITEM', $roots->item($i));
                for ($j = 0; $j < $products->length; $j++) {
                    $node = $products->item($j);

                    if ( $xpath->evaluate('./@id', $node)->length >= 1 ) {
                        $shoptet_id = $xpath->query('./@id', $node)->item(0)->nodeValue;
                    } else {
                        $shoptet_id = NULL;
                    }

                    if ( $xpath->evaluate('./NAME', $node)->length >= 1 ) {
                        $name =  $xpath->query('./NAME', $node)->item(0)->nodeValue;
                    } else {
                        $name = NULL;
                    }

                    if ( $xpath->evaluate('./CODE', $node)->length >= 1 ) {
                        $code =  $xpath->query('./CODE', $node)->item(0)->nodeValue;
                    } else {
                        $code = NULL;
                    }

                    if ( $xpath->evaluate('./SHORT_DESCRIPTION', $node)->length >= 1 ) {
                        $short_description =  $xpath->query('./SHORT_DESCRIPTION', $node)->item(0)->nodeValue;
                    } else {
                        $short_description = NULL;
                    }

                    if ( $xpath->evaluate('./DESCRIPTION', $node)->length >= 1 ) {
                        $description =  $xpath->query('./DESCRIPTION', $node)->item(0)->nodeValue;
                    } else {
                        $description = NULL;
                    }

                    if ( $xpath->evaluate('./CATEGORIES', $node)->length >= 1 ) {
                        $categories = [];
                        $categoriesRoot = $xpath->query('./CATEGORIES/CATEGORY', $node);

                        if ( $xpath->evaluate('./CATEGORIES/DEFAULT_CATEGORY', $node)->length >= 1 ) {
                            $default_category =  $xpath->query('./CATEGORIES/DEFAULT_CATEGORY', $node)->item(0)->nodeValue;
                        } else {
                            $default_category = NULL;
                        }
                        
                        if ($categoriesRoot->length > 0) {
                            for ($k = 0; $k < $categoriesRoot->length; $k++) {
                                $category =  $xpath->query('./CATEGORIES/CATEGORY', $node)->item($k)->nodeValue;
                                array_push($categories, $category);
                            };
                        }
                        
                        $categories = implode('; ', $categories);
                    } else {
                       $categories = NULL;
                    };

                    if ( $xpath->evaluate('./IMAGES', $node)->length >= 1 ) {
                        $images = [];
                        $imagesRoot = $xpath->query('./IMAGES/IMAGE', $node);
                        
                        if ($imagesRoot->length > 0) {
                            for ($k = 0; $k < $imagesRoot->length; $k++) {
                                $image =  $xpath->query('./IMAGES/IMAGE', $node)->item($k)->nodeValue;
                                array_push($images, $image);
                            };
                        }
                        
                        $images = implode('; ', $images);
                    } else {
                       $images = NULL;
                    };

                    $values = [
                        'name' => $name,
                        'shoptet_id' => $shoptet_id,
                        'categories' => $categories,
                        'default_category' => $default_category,
                        'code' => $code,
                        'short_description' => $short_description,
                        'description' => $description,
                        'images' => $images,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ];

                    array_push($args, $values);
                }
            }
        }
        DB::table('stock_shoptet')->insert($args);
    }

    public function parsePohodaXml($xml)
    {
        $carbon = $this->carbon;
        $xpath = Helper::createXmlDom($xml);
    	$roots = $xpath->query('//rsp:responsePackItem/lStk:listStock');
    	if ($roots->length > 0) {
    	    for ($i = 0; $i < $roots->length; $i++) {
    	        $products = $xpath->query('./lStk:stock', $roots->item($i));
    	        for ($j = 0; $j < $products->length; $j++) {
    	            $node = $products->item($j);
    	            $id = $xpath->query('./stk:stockHeader/stk:id', $node)->item(0)->nodeValue;
    	            $name = trim($xpath->query('./stk:stockHeader/stk:name', $node)->item(0)->nodeValue);
    	            $code = trim(@$xpath->query('./stk:stockHeader/stk:code', $node)->item(0)->nodeValue);
    	            $ean = @$xpath->query('./stk:stockHeader/stk:EAN', $node)->item(0)->nodeValue;
    	            $unit = @$xpath->query('./stk:stockHeader/stk:unit', $node)->item(0)->nodeValue;
    	            $mass = @$xpath->query('./stk:stockHeader/stk:mass', $node)->item(0)->nodeValue;
    	            $quantity = @$xpath->query('./stk:stockHeader/stk:count', $node)->item(0)->nodeValue;
    	            $sellingPrice = $xpath->query('./stk:stockHeader/stk:sellingPrice', $node)->item(0)->nodeValue;
    	            $description = @$xpath->query('./stk:stockHeader/stk:description', $node)->item(0)->nodeValue;

    	            $categoryIds = array();
    	            $categories = $xpath->query('./stk:stockHeader/stk:categories/stk:idCategory', $node);
    	            if ($categories) {
    	                for ($k = 0; $k < $categories->length; $k++) {
    	                    $category = (int) $categories->item($k)->nodeValue;
    	                    if ($category > 0) {
    	                        $categoryIds[] = $category;
    	                    }
    	                }
    	            }

    	            $parameters = array();
    	            $params = $xpath->query('./stk:stockHeader/stk:intParameters/stk:intParameter', $node);
    	            if ($params->length > 0) {
    	                for ($k = 0; $k < $params->length; $k++) {
    	                    $param_name = trim($xpath->query('./stk:intParameterName', $params->item($k))->item(0)->nodeValue);
    	                    $param_value = trim($xpath->query('./stk:intParameterValues/stk:intParameterValue/stk:parameterValue', $params->item($k))->item(0)->nodeValue);
    	                    if (!empty($param_value)) {
    	                        $param_value = $param_value == 'true' ? 'áno' : $param_value;
    	                        $param_value = $param_value == 'false' ? 'nie' : $param_value;
    	                        $parameters[$param_name] = $param_value;
    	                    }
    	                }
    	            }

    	            $prices = array();
    	            $price_items = $xpath->query('./stk:stockPriceItem/stk:stockPrice', $node);
    	            if ($price_items->length > 0) {
    	                for ($k = 0; $k < $price_items->length; $k++) {
    	                    $price_type = $xpath->query('./typ:ids', $price_items->item($k))->item(0)->nodeValue;
    	                    $price_value = $xpath->query('./typ:price', $price_items->item($k))->item(0)->nodeValue;
    	                    if (!empty($price_value)) {
    	                        $prices[$price_type] = $price_value;
    	                    }
    	                }
    	            }

    	            $related = array();
    	            $related_items = $xpath->query('./stk:stockHeader/stk:relatedStocks/stk:idStocks', $node);
    	            if ($related_items->length > 0) {
    	                for ($k = 0; $k < $related_items->length; $k++) {
    	                    $related_id = $xpath->query('./typ:stockItem/typ:id', $related_items->item($k))->item(0)->nodeValue;
    	                    if (!empty($related_id)) {
    	                        $related[] = $related_id;
    	                    }
    	                }
    	            }


    	            if (empty($name)) {
    	                continue;
    	            }

    	            if (empty($categoryIds[0])) {
    	                continue;
    	            }

    	            $pictures = $xpath->query('./stk:stockHeader/stk:pictures/stk:picture', $node);
    	            
    	            for ($k = 0; $k < $pictures->length; $k++) {
    	            	$node = $pictures->item($k);
    	            	$picture = $xpath->query('./stk:filepath', $node)->item(0)->nodeValue;
    	            }


                    DB::table('stock')->insertGetId(
                        [
                            'item_id' => $id,
                            'title' => $name,
                            'code' => $code,
                            'ean' => $ean,
                            'sellingPrice' => $sellingPrice,
                            'description' => $description,
                            'created_at' => $carbon->now(),
                            'updated_at' => $carbon->now()
                        ]
                    );

    	            // $picture = @$xpath->query('./stk:stockHeader/stk:pictures/stk:picture[@default="true"]/stk:filepath', $node)->item(0)->nodeValue;

    	        }
    	    }
    	}
    }

    public static function backupImages($systemName)
    {
        switch ($systemName) {
            case 'shoptet':
                DB::table('stock_shoptet')->select('shoptet_id','images')->orderBy('id')->chunk(30, function ($query)
                {
                    foreach ($query as $row) {

                        if ($row->images !== NULL) {
                            $images = explode('; ', $row->images);
                            $shoptet_id = $row->shoptet_id;
                                               
                            foreach ($images as $image) {
                                $url = $image;
                                $name = substr($url, strrpos($url, '/') + 1);
                                $appendix = substr($name, strpos($name, '?'));
                                $name = str_replace($appendix, '', $name);

                                $contents = file_get_contents($url);
                                Storage::put("./shoptetImages/{$shoptet_id}/{$name}", $contents);
                            }
                        };

                        continue;
                        
                    }
                });
                break;
            
            default:
                # code...
                break;
        }
    }
}
