<?php


namespace App\Services;

use SimpleXMLElement;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ProductServices
{
    public function updateProduct()
    {
        $client = new Client();

        $response = $client->request('GET', 'https://api.midocean.com/gateway/products/2.0?language=en',
            [
                'headers' => [
                    'User-Agent' => ' Example REST API Client',
                    'x-Gateway-APIKey' => '66ab1e83-b57b-4587-9691-167638201611',
                ]
            ]);

        $file = json_decode($response->getBody(), true);
        $xmlArr = [];

//        $xmlDoc = new \DOMDocument();
        $xml = new SimpleXMLElement('<xml/>');
        foreach ($file as $f) {
            $product_name = !empty($f['product_name']) ? $f['product_name'] : "/";
            $long_description = !empty($f['long_description']) ? $f['long_description'] : "/";
            $dimensions = !empty($f['dimensions']) ? $f['dimensions'] : "/";
            $material = !empty($f['material']) ? $f['material'] : "/";
            $category_code = !empty($f['category_code']) ? $f['category_code'] : "/";
            $country_of_origin = !empty($f['country_of_origin']) ? $f['country_of_origin'] : "/";
            $category_level3 = !empty($f['variants'][0]['category_level3']) ? $f['variants'][0]['category_level3'] : "/";
            $image = !empty($f['variants'][0]['digital_assets'][2]['url']) ? $f['variants'][0]['digital_assets'][2]['url'] : "/";
            $imagType = !empty($f['variants'][0]['digital_assets'][2]['type']) ? $f['variants'][0]['digital_assets'][2]['type'] : "/";
            $subType = !empty($f['variants'][0]['digital_assets'][2]['subtype']) ? $f['variants'][0]['digital_assets'][2]['subtype'] : "/";
            $image2 = !empty($f['variants'][0]['digital_assets'][0]['url']) ? $f['variants'][0]['digital_assets'][0]['url'] : "/";
            $imagType2 = !empty($f['variants'][0]['digital_assets'][0]['type']) ? $f['variants'][0]['digital_assets'][0]['type'] : "/";
            $subType2 = !empty($f['variants'][0]['digital_assets'][0]['subtype']) ? $f['variants'][0]['digital_assets'][0]['subtype'] : "/";
            $inner_carton_quantity = !empty($f['inner_carton_quantity']) ? $f['inner_carton_quantity'] : "/";
            $carton_length = !empty($f['carton_length']) ? $f['carton_length'] : "/";
            $carton_width = !empty($f['carton_width']) ? $f['carton_width'] : "/";
            $carton_height = !empty($f['carton_height']) ? $f['carton_height'] : "/";
            $carton_height_unit = !empty($f['carton_height_unit']) ? $f['carton_height_unit'] : "/";
            $carton_gross_weight = !empty($f['carton_gross_weight']) ? $f['carton_gross_weight'] : "/";
            $carton_gross_weight_unit = !empty($f['carton_gross_weight_unit']) ? $f['carton_gross_weight_unit'] : "/";
            $carton_volume = !empty($f['carton_volume']) ? $f['carton_volume'] : "/";
            $outer_carton_quantity = !empty($f['outer_carton_quantity']) ? $f['outer_carton_quantity'] : "/";
//            return $f;

            $xmlArr = $xml->addChild('PRODUCT');
            $xmlArr->addChild('PRODUCT_NUMBER', $f['variants'][0]['sku']);
            $xmlArr->addChild('PRODUCT_BASE_NUMBER', $f['master_code']);
            $xmlArr->addChild('PRODUCT_ID', $f['variants'][0]['variant_id']);
            $xmlArr->addChild('PRODUCT_PRINT_ID', $f['master_id']);
            $xmlArr->addChild('PRODUCT_NAME', htmlspecialchars($product_name));
            $xmlArr->addChild('PLCSTATUS', $f['variants'][0]['plc_status_description']);
            $xmlArr->addChild('SHORT_DESCRIPTION', htmlspecialchars($f['short_description']));
            $xmlArr->addChild('LONG_DESCRIPTION', htmlspecialchars($long_description));
            $xmlArr->addChild('DIMENSIONS', $dimensions);
            $xmlArr->addChild('NET_WEIGHT', $f['net_weight']);
            $xmlArr->addChild('GROSS_WEIGHT', $f['gross_weight']);
            $xmlArr->addChild('GROSS_WEIGHT_UNIT', $f['gross_weight_unit']);
            $xmlArr->addChild('COLOR_CODE', $f['variants'][0]['color_code']);
            $xmlArr->addChild('MATERIAL_TYPE', $material);
            $xmlArr->addChild('CATEGORY_CODE', htmlspecialchars($category_code));
            $xmlArr->addChild('CATEGORY_LEVEL_1', htmlspecialchars($f['variants'][0]['category_level1']));
            $xmlArr->addChild('CATEGORY_LEVEL_2', htmlspecialchars($f['variants'][0]['category_level2']));
            $xmlArr->addChild('CATEGORY_LEVEL_3', htmlspecialchars($category_level3));
            $xmlArr->addChild('IMAGE_URL', $image);
            $xmlArr->addChild('COMMODITY_CODE', $f['commodity_code']);
            $xmlArr->addChild('COUNTRY_OF_ORIGIN', $country_of_origin);
            $packaging = $xmlArr->addChild('PACKAGING_CARTON');
            $packaging->addChild('LENGTH', $carton_length);
            $packaging->addChild('WIDTH', $carton_width);
            $packaging->addChild('HEIGHT', $carton_height);
            $packaging->addChild('SIZE_UNIT', $carton_height_unit);
            $packaging->addChild('WEIGHT', $carton_gross_weight);
            $packaging->addChild('WEIGHT_UNIT', $carton_gross_weight_unit);
            $packaging->addChild('VOLUME', $carton_volume);
            $packaging->addChild('VOLUME_UNIT', $carton_gross_weight_unit);
            $packaging->addChild('INNER_CARTON_QUANTITY', $inner_carton_quantity);
            $packaging->addChild('CARTON_QUANTITY', $outer_carton_quantity);
            $digital_asset = $xmlArr->addChild('DIGITAL_ASSETS');
            $digital_assets = $digital_asset->addChild('DIGITAL_ASSET');
            $digital_assets->addChild('URL', $image);
            $digital_assets->addChild('TYPE', $imagType);
            $digital_assets->addChild('SUBTYPE', $subType);
            $digital_asset2 = $digital_asset->addChild('DIGITAL_ASSET');
            $digital_asset2->addChild('URL', $image);
            $digital_asset2->addChild('TYPE', $imagType2);
            $digital_asset2->addChild('URL', $subType2);

        }
        $xml->saveXML('product.xml');
    }
}
