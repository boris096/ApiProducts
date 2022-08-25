<?php

namespace App\Http\Controllers;

use Faker\Provider\File;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
//use phpDocumentor\Reflection\File;
use SimpleXMLElement;
use SoapBox\Formatter\Formatter;
use Spatie\ArrayToXml\ArrayToXml;

class ApiProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $curl = curl_init();

//        $headers = [
//            'User-Agent: Example REST API Client',
//            'x-Gateway-APIKey:  66ab1e83-b57b-4587-9691-167638201611'
//        ];
//
//        return file_get_contents(storage_path('/app/public/test.xml'));
//        die();
        $headers =  [
            'User-Agent: Example REST API Client',
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            'x-Gateway-APIKey'       => '66ab1e83-b57b-4587-9691-167638201611',
        ];
        $client = new Client();

        $response = $client->request('GET','https://api.midocean.com/gateway/products/2.0?language=en',
            [
                'headers' => [
                    'User-Agent'=>' Example REST API Client',
                    'x-Gateway-APIKey'       => '66ab1e83-b57b-4587-9691-167638201611',
                ]
            ]);

        $file = json_decode($response->getBody(),true);
        $xmlArr = [];

//        $xmlDoc = new \DOMDocument();
        $xml = new SimpleXMLElement('<xml/>');
        foreach ($file as $f){
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
            $xmlArr->addChild('CATEGORY_CODE',  htmlspecialchars($category_code));
            $xmlArr->addChild('CATEGORY_LEVEL_1', htmlspecialchars($f['variants'][0]['category_level1']));
            $xmlArr->addChild('CATEGORY_LEVEL_2', htmlspecialchars($f['variants'][0]['category_level2']));
            $xmlArr->addChild('CATEGORY_LEVEL_3', htmlspecialchars($category_level3));
            $xmlArr->addChild('IMAGE_URL', $image);
            $xmlArr->addChild('COMMODITY_CODE', $f['commodity_code']);
            $xmlArr->addChild('COUNTRY_OF_ORIGIN', $country_of_origin);
            $packaging = $xmlArr->addChild('PACKAGING_CARTON');
            $packaging->addChild('LENGTH',$f['carton_length']);
            $packaging->addChild('WIDTH',$f['carton_width']);
            $packaging->addChild('HEIGHT',$f['carton_height']);
            $packaging->addChild('SIZE_UNIT',$f['carton_height_unit']);
            $packaging->addChild('WEIGHT',$f['carton_gross_weight']);
            $packaging->addChild('WEIGHT_UNIT',$f['carton_gross_weight_unit']);
            $packaging->addChild('VOLUME',$f['carton_volume']);
            $packaging->addChild('VOLUME_UNIT',$f['carton_gross_weight_unit']);
            $packaging->addChild('INNER_CARTON_QUANTITY',$inner_carton_quantity);
            $packaging->addChild('CARTON_QUANTITY',$f['outer_carton_quantity']);
            $digital_asset = $xmlArr->addChild('DIGITAL_ASSETS');
            $digital_assets = $digital_asset->addChild('DIGITAL_ASSET');
            $digital_assets->addChild('URL',$image);
            $digital_assets->addChild('TYPE',$imagType);
            $digital_assets->addChild('SUBTYPE',$subType);
            $digital_asset2 = $digital_asset->addChild('DIGITAL_ASSET');
            $digital_asset2->addChild('URL',$image);
            $digital_asset2->addChild('TYPE',$imagType2);
            $digital_asset2->addChild('URL',$subType2);


//            $new = $xmlArr->addChild('PRODUCT2');
//            $new->addChild('test','test');
//            $test = $new->addChild('taraba');
//            $test->addChild('taraba2','vrijednost');
//            $test->addChild('taraba2','vrijednost');
//            $xmlArr->addChild('PRODUCT');



//            return $root;

//            Header('Content-type: text/xml');
//            return ($xml->asXML());
//            $xmlArr[] = [
//                'PRODUCT' => [
//                          'PRODUCT_NUMBER' => strval($f['variants'][0]['sku']),
//                          'PRODUCT_BASE_NUMBER' => strval($f['master_code']),
//                          'PRODUCT_ID' => strval($f['variants'][0]['variant_id']),
//                          'PRODUCT_PRINT_ID' => strval($f['master_id']),
//                          'PRODUCT_NAME' => strval($product_name),
//                          'PLCSTATUS' => strval($f['variants'][0]['plc_status_description']),
//                          'SHORT_DESCRIPTION'=> strval($f['short_description']),
//                          'LONG_DESCRIPTION' => strval($long_description),
//                          'DIMENSIONS' => strval($dimensions),
//                          'NET_WEIGHT'=> strval($f['net_weight']),
//                          'GROSS_WEIGHT'=> strval($f['gross_weight']),
//                          'GROSS_WEIGHT_UNIT' => strval($f['gross_weight_unit']),
//                          'COLOR_CODE' => strval($f['variants'][0]['color_code']),
//                          'MATERIAL_TYPE' => strval($material),
//                          'CATEGORY_CODE' => strval($category_code),
//                          'CATEGORY_LEVEL_1' => strval($f['variants'][0]['category_level1']),
//                          'CATEGORY_LEVEL_2' => strval($f['variants'][0]['category_level2']),
//                          'CATEGORY_LEVEL_3' => strval($category_level3),
//                          'IMAGE_URL' => strval($image),
//                          'COMMODITY_CODE' => strval($f['commodity_code']),
//                          'COUNTRY_OF_ORIGIN' => strval($country_of_origin),
//                          'PACKAGING_CARTON' => [
//                            'LENGTH' => strval($f['carton_length']),
//                            'WIDTH' => strval($f['carton_width']),
//                            'HEIGHT' => strval($f['carton_height']),
//                            'SIZE_UNIT' => strval($f['carton_height_unit']),
//                            'WEIGHT' => strval($f['carton_gross_weight']),
//                            'WEIGHT_UNIT' => strval($f['carton_gross_weight_unit']),
//                            'VOLUME' => strval($f['carton_volume']),
//                            'VOLUME_UNIT' => strval($f['carton_volume_unit']),
//                            'INNER_CARTON_QUANTITY' => strval($inner_carton_quantity),
//                            'CARTON_QUANTITY' => strval($f['outer_carton_quantity']),
//                          ],
//                      'DIGITAL_ASSETS' => [
//                        'DIGITAL_ASSET' => [
//                            'URL' => $image ,
//                            'TYPE' => $imagType,
//                            'SUBTYPE' => $subType,
//                        ],
//                        'DIGITAL_ASSET' => [
//                            'URL' => strval($image2),
//                            'TYPE' => strval($imagType2),
//                            'SUBTYPE' => strval($subType2),
//                            ],
//                        ],
//                      ]
//                ];

        }
        $xml->saveXML('product.xml');
//        $xml->save();
//        Storage::put('product.xml',$xml);

//        $xml = new SimpleXMLElement('<root/>');
//        array_walk($xmlArr, array ($xml, 'addChild'));
//        return $xml->asXML();
//        file_put_contents('test.xml',$xmlArr);
//        $xml   = $formatter->toXml();
//        Storage::disk('public')->put('test.xml', var_export($xmlArr));
//        $arrayToXml = new ArrayToXml($xmlArr);
//        $result = $arrayToXml->toXml();
//        return $result;
//        return ArrayToXml::convert($xmlArr,'PRODUCT' );

//       Storage::put(storage_path('public'),$xml);
//        $fp = fopen(storage_path('/app/public/test.xml'),'w');
//        fwrite($fp,var_export($xmlArr,true));
//        return $data;

//        $test = [];
////        if (is_array($data) || is_object($data)) {
//            foreach ($dataTest as $content) {
//                return $content;
//            }
//        }else{
//            return "nije";
//        }

//        return $kontent['master_code'];

//        return view('apiProduct',[
//            "response" => $response
//        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    function callAPI(){
//        $client = new \GuzzleHttp\Client();
////        $res = $client->get( 'https://api.midocean.com/gateway/products/2.0?language=en', [
////            'form_params' => [
////                'Authorization' => 'key=66ab1e83-b57b-4587-9691-167638201611',
////                'Content-Type' => 'application/json'
//////                'secret' => 'test_secret',
////            ]
////        ]);
//        $res = $client->get('https://api.midocean.com/gateway/products/2.0?language=en', [
//            'headers' =>  ['x-Gateway-APIKey', '66ab1e83-b57b-4587-9691-167638201611'],
//            'Accept'     => 'application/json'
//            ]);
//
////        $request->setHeader('x-Gateway-APIKey', "66ab1e83-b57b-4587-9691-167638201611");
////
////        $response = $client->send($request);
//
//        return view('apiProduct',[
//            "response" => $res
//        ]);
    }
}
