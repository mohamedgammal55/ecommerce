<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider ;
use App\Models\Subcategory ;
use App\Models\Api_notification ;
use App\Models\TopSlider;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    //
    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */
    public function slider(Request $request){
        $data = Slider::orderby('id','DESC')->get();
        $resultJson = ["data" => $data, "message" => "", "status" => 200];
        return response()->json($resultJson, 200);
    }

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */
    public function GetProducts(Request $request){
        $products =  Subcategory::where('category_id',$request->category_id)
            ->with('products')->get();

        if ($request->lang == "1"){
            foreach ($products as $lang){
                foreach ($lang->products as $product){
                    $product->name = $product->name_EN;
                }
                $lang->name = $lang->nameEN;
            }
        }
        $resultJson = ["data" => $products, "message" => "", "status" => 200];
        return response()->json($resultJson, 200);
    }//end fun

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetTopSliderKeys(){
        $get = TopSlider::
        inRandomOrder()->limit(9)->
        get();
        $resultJson = ["data" => $get, "message" => "", "status" => 200];
        return response()->json($resultJson, 200);
    }//end fun

    public function notifications(Request $request){


        $data = Api_notification::where('to_user_id',$request->to_user_id)->get();

        $resultJson = ["data" => $data, "message" => "", "status" => 200];
        return response()->json($resultJson, 200);
    }//end fun




} // end class
