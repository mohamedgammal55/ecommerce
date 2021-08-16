<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use App\Models\Banner;
use App\Models\BottomSlider;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Subcategory;
use Illuminate\Http\Request;


class ProductsController extends Controller
{

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */

    public function offers(Request $request)
    {
        $user_id = ($request->user_id) ? $request->user_id : "0";
        $withWishlist = [
            'is_wishlist' => function ($query) use ($user_id) {
                return $query->where(["user_id" => $user_id]);
            }];
        $data = Product::where("is_discount", 1)
            ->where("discount_date", ">=", date("m/d/Y", time()))
            ->with($withWishlist)
            ->get();

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

    public function feature(Request $request)
    {
        $user_id = ($request->user_id) ? $request->user_id : "0";
        $withWishlist = [
            'is_wishlist' => function ($query) use ($user_id) {
                return $query->where(["user_id" => $user_id]);
            }];
        $data = Product::where("featured", 1)->with($withWishlist)->get();
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
    public function best(Request $request)
    {
        $user_id = ($request->user_id) ? $request->user_id : "0";
        $withWishlist = [
            'is_wishlist' => function ($query) use ($user_id) {
                return $query->where(["user_id" => $user_id]);
            }];
        $data = Product::where("best", 1)->with($withWishlist)->get();
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
    public function home(Request $request)
    {
        $user_id = ($request->user_id) ? $request->user_id : "0";
        $withWishlist = [
            'is_wishlist' => function ($query) use ($user_id) {
                return $query->where(["user_id" => $user_id]);
            }];
        //-------------------------------------
        $data['discount'] = Product::where("is_discount", 1)->where("discount_date", ">=", date("m/d/Y", time()))
            ->with($withWishlist)->orderBy("id", "DESC")->limit(10)->get();
        $data['featured'] = Product::where("featured", 1)
            ->with($withWishlist)->orderBy("id", "DESC")->limit(10)->get();
        $data['best'] = Product::where("best", 1)->with($withWishlist)->orderBy("id", "DESC")->limit(10)->get();
        $data["bannar"] = Banner::where("type", 'TopSmall')->first();
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
    public function oneProduct(Request $request)
    {
        $user_id = ($request->user_id) ? $request->user_id : "0";
        $product_id = ($request->product_id) ? $request->product_id : "0";
        $withWishlist = ['galleries',
            'comments'=>function ($query) use ($user_id) {
                return $query->with("user")->orderBy("id","DESC");
            },
            'is_wishlist' => function ($query) use ($user_id) {
                return $query->where(["user_id" => $user_id]);
            }];
        $data = Product::where("id", $product_id)->withCount("comments")->with($withWishlist)->first();
        if ($request->lang == "1"){
            if ($data->name_EN == null or $data->details_EN == null){
                $resultJson = ["data" => $data, "message" => "", "status" => 200];
                return response()->json($resultJson, 200);
            }
                $data->name = $data->name_EN;

                $data->details = $data->details_EN;

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
    public function getProductByDep(Request $request)
    {
        $user_id = ($request->user_id) ? $request->user_id : "0";
        //----------------------------
        $category_id = ($request->category_id) ? $request->category_id : "all";
        $subcategory_id = ($request->subcategory_id) ? $request->subcategory_id : "all";
        $childcategory_id = ($request->childcategory_id) ? $request->childcategory_id : "all";
        $search_name = ($request->search_name) ? $request->search_name : "all";
        //----------------------------
        $withWishlist = [
            'is_wishlist' => function ($query) use ($user_id) {
                return $query->where(["user_id" => $user_id]);
            }];
        $query = Product::with($withWishlist) ;
        //----------------------------
        if ($category_id != "all") {
            $query = $query->where("category_id",$category_id);
        }
        if ($subcategory_id != "all") {
            $query = $query->where("subcategory_id",$subcategory_id);
        }
        if ($childcategory_id != "all") {
            $query = $query->where("childcategory_id",$childcategory_id);
        }
        if($search_name != "all"){
            $query = $query->where('name', 'like', '%' . $search_name . '%');
        }
        //----------------------------
        $data = $query->get();
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


    public function getProductByName(Request $request)
    {
        $user_id = ($request->user_id) ? $request->user_id : "0";
        //----------------------------
        $search_name = ($request->search_name) ? $request->search_name : "all";
        //----------------------------
        $withWishlist = [
            'is_wishlist' => function ($query) use ($user_id) {
                return $query->where(["user_id" => $user_id]);
            }];
        //----------------------------
        $data = Product::where('name', 'like', '%' . $search_name . '%')
            ->with($withWishlist)->get();
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

    public function myWishlists(Request $request)
    {
        $user_id = ($request->user_id) ? $request->user_id : "0";
        //----------------------------
        $withWishlist = [
            'is_wishlist' => function ($query) use ($user_id) {
                return $query->where(["user_id" => $user_id]);
            }];
        //----------------------------
        $wishlist = Wishlist::where("user_id",$user_id)->pluck("product_id")->toArray();
        $data = Product::whereIn('id',$wishlist)->with($withWishlist)->get();
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

    public function actionWishlists(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }
        //----------------------------------
        $resultJson = ["data" => null, "message" => "", "status" => 200];
        $wish = Wishlist::where("user_id",$request->user_id)->where("product_id",$request->product_id)->first();
        if(isset($wish->id)){
           Wishlist::where("id",$wish->id)->delete();
            $resultJson["message"] = "success remove from Wishlists";
            return response()->json($resultJson, 200);
        }
        $data["user_id"] = $request->user_id;
        $data["product_id"] = $request->product_id;
        $wishList = Wishlist::create($data);
        $resultJson["data"] = $wishList;
        $resultJson["message"] = "success add to Wishlists";
        return response()->json($resultJson, 200);
    }//end fun
    /**
     * @param Request $request
     *
     */
    public function GetOtherProducts(Request $request){
        $user_id = ($request->user_id) ? $request->user_id : "0";
        $withWishlist = [
            'is_wishlist' => function ($query) use ($user_id) {
                return $query->where(["user_id" => $user_id]);
            }];
        $get = Product::where('is_discount',0)
            ->where('best',0)->where('featured',0)->with($withWishlist)->get();
        if ($request->lang == "1"){
            foreach ($get as $lang){
                $lang->name = $lang->name_EN;
            }
        }
        $resultJson = ["data" => $get, "message" => "", "status" => 200];
        return response()->json($resultJson, 200);
    }//end fun
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetCategoryAndThemSup(Request $request){

        $data = Category::with("subs")->get();
        if ($request->lang == "1"){
            foreach ($data as $lang){
                $lang->name = $lang->namEN;
                foreach ($lang->subs as $subs){
                    $subs->name == $subs->namEN;
                }
            }
        }
        $resultJson = ["data" => $data, "message" => "", "status" => 200];
        return response()->json($resultJson, 200);
    }//end fun

    public function GetproductAndThemSup(Request $request){
        $user_id = ($request->user_id) ? $request->user_id : "0";
        $withWishlist = [
            'products.is_wishlist' => function ($query) use ($user_id) {
                return $query->where(["user_id" => $user_id]);
            }];
        $data = Subcategory::with('products','childs.products')->with($withWishlist)
            ->find($request->sub_id);
        $resultJson = ["data" => $data, "message" => "", "status" => 200];
        return response()->json($resultJson, 200);

    }//end fun

    public function GetTopSliderProducts(){
        $products = Product::where('is_discount',1)
            ->orwhere('best',1)->orwhere('featured',41)
            ->orwhere('top',1)->orwhere('hot',1)
            ->orwhere('latest',1)->orwhere('big',1)
            ->orwhere('trending',1)->orwhere('sale',1)->get();

        $resultJson = ["data" => $products, "message" => "", "status" => 200];
        return response()->json($resultJson, 200);
    }//end fun
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetBottomSlider(){
        $data = BottomSlider::orderby('id','desc')->get();
        $resultJson = ["data" => $data, "message" => "", "status" => 200];
        return response()->json($resultJson, 200);
    }//end fun


    public function GetProductsByKey(Request $request){
        $user_id = ($request->user_id) ? $request->user_id : "0";
        $withWishlist = [
            'is_wishlist' => function ($query) use ($user_id) {
                return $query->where(["user_id" => $user_id]);
            }];

        $products = Product::where($request->key,1)->with($withWishlist)->get();

        $resultJson = ["data" => $products, "message" => "", "status" => 200];
        return response()->json($resultJson, 200);

    }//end fun

} // end class
