<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;


class CategoriesController extends Controller
{
    //
    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */
    public function main(Request $request){
        $data = Category::where('status',1)->orderby('id','DESC')->get();
        if ($request->lang == "1"){
            foreach ($data as $lang){
                $lang->name = $lang->name_en;
            }
        }

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
    public function sub(Request $request){
        $data = Subcategory::get();
        if ($request->lang == "1"){
            foreach ($data as $lang){
                $lang->name = $lang->nameEN;
            }
        }
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

    public function child(Request $request){
        $data = Childcategory::get();

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

    public function subWithChild(Request $request){
        $data = Subcategory::where("category_id",$request->category_id)->with("childs")->get();
        if ($request->lang == "1"){
            foreach ($data as $lang){
                $lang->name = $lang->name_EN;
            }
        }
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


    public function GetCategoryAndThemSup(Request $request){

        $data = Category::with("subs")->get();


        $resultJson = ["data" => $data, "message" => "", "status" => 200];
        return response()->json($resultJson, 200);
    }//end fun

} // end class
