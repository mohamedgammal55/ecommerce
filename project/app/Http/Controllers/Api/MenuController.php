<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class MenuController extends Controller
{
    public function StoreMenu(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'status' => 'required',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }
        $count = Menu::where('product_id',$request->product_id)
            ->where('user_id',$request->user_id)->count();

        if ($count != 0){
            $find = Menu::where('product_id',$request->product_id)
                ->where('user_id',$request->user_id)->first();
            $get = Menu::where('product_id',$request->product_id)
                ->where('user_id',$request->user_id)
                ->update([
                    'amount' =>  $find->amount + \request('amount')
                ]);
            return response()->json(['data'=>$find,'message'=>"","status"=>200],200);
        }


        $data['user_id'] = $request->user_id;
        $data['product_id'] = $request->product_id;
        $data['status'] = $request->status;
        $data['amount'] = $request->amount;
        $menu = Menu::create($data);
        return response()->json(['data'=>$menu,'message'=>"","status"=>200],200);
    }//end fun

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteMenu(Request $request){
        $validator = Validator::make($request->all(), [
            'menu_id' => 'required|exists:menu,id',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }
        $menu = Menu::find($request->menu_id)->delete();
        return response()->json(['data'=>"",'message'=>"Deleted","status"=>200],200);
    }//end fun
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetMenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }
        $menu = Menu::where('user_id', $request->user_id)->with('product')->get();



        $total = 0;
        foreach($menu as $value){
            $total = $total + ($value->product->price * $value->amount);
        }
        return response()->json(['data' => $menu,"total"=>$total,'message'=>"","status"=>200], 200);
    }//end fun

}
