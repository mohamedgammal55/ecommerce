<?php

namespace App\Http\Controllers\Api;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\Pagesetting;
use App\Models\PaymentGateway;
use App\Models\Pickup;
// use App\Models\Menu;
use App\Models\Product;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\VendorOrder;
use Auth;
use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Session;
use Validator;

class OrderControlller extends Controller
{
    public function StoreOrder(Request $request){

//        return $request;
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }




        $myCart = [];

        $totalQuanty = count($request->cart);

        foreach ($request->cart as $cartid) {


                // Menu::where('product_id',$cartid["id"])
                //     ->where('user_id',$request->user_id)
                //     ->update([
                //         'status' =>  'yes'
                //     ]);

            $prod = Product::where('id', '=', $cartid["id"])->first(['id', 'user_id', 'slug', 'name', 'photo', 'size', 'size_qty', 'size_price', 'color', 'price', 'stock', 'type', 'file', 'link', 'license', 'license_qty', 'measure', 'whole_sell_qty', 'whole_sell_discount', 'attributes']);

            // Set Attrubutes

            if (Session::has('language')) {
                $data = \DB::table('languages')->find($request->lang);
                $data_results = file_get_contents(public_path() . '/assets/languages/' . $data->file);
                $lang = json_decode($data_results);

            } else {
                $data = \DB::table('languages')->where('is_default', '=', 1)->first();
                $data_results = file_get_contents(public_path() . '/assets/languages/' . $data->file);
                $lang = json_decode($data_results);

            }


            $keys = '';
            $values = '';
            if (!empty($prod->license_qty)) {
                $lcheck = 1;
                foreach ($prod->license_qty as $ttl => $dtl) {
                    if ($dtl < 1) {
                        $lcheck = 0;
                    } else {
                        $lcheck = 1;
                        break;
                    }
                }
                if ($lcheck == 0) {
                    return redirect()->route('front.cart')->with('unsuccess', $lang->out_stock);
                }
            }

            // Set Size

            $size = '';
            if (!empty($prod->size)) {
                $size = trim($prod->size[0]);
            }
            $size = str_replace(' ', '-', $size);

            // Set Color

            $color = '';
            if (!empty($prod->color)) {
                $color = $prod->color[0];
                $color = str_replace('#', '', $color);
            }


            if ($prod->user_id != 0) {
                $gs = Generalsetting::findOrFail(1);
                $prc = $prod->price + $gs->fixed_commission + ($prod->price / 100) * $gs->percentage_commission;
                $prod->price = round($prc, 2);
            }

            // Set Attribute


            if (!empty($prod->attributes)) {
                $attrArr = json_decode($prod->attributes, true);

                $count = count($attrArr);
                $i = 0;
                $j = 0;
                if (!empty($attrArr)) {
                    foreach ($attrArr as $attrKey => $attrVal) {

                        if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                            if ($j == $count - 1) {
                                $keys .= $attrKey;
                            } else {
                                $keys .= $attrKey . ',';
                            }
                            $j++;

                            foreach ($attrVal['values'] as $optionKey => $optionVal) {

                                $values .= $optionVal . ',';

                                $prod->price += $attrVal['prices'][$optionKey];
                                break;


                            }

                        }
                    }

                }

            }
            $keys = rtrim($keys, ',');
            $values = rtrim($values, ',');


            $oldCart = !empty($myCart) ? $myCart : null;
            $cart = new Cart($oldCart);

            $cart->add($prod, $prod->id, $size, $color, $keys, $values);
            if ($cart->items[$cartid["id"] . $size . $color . str_replace(str_split(' ,'), '', $values)]['dp'] == 1) {
                return redirect()->route('front.cart')->with('unsuccess', $lang->already_cart);
            }
            if ($cart->items[$cartid["id"] . $size . $color . str_replace(str_split(' ,'), '', $values)]['stock'] < 0) {
                return redirect()->route('front.cart')->with('unsuccess', $lang->out_stock);
            }
            if (!empty($cart->items[$cartid["id"] . $size . $color . str_replace(str_split(' ,'), '', $values)]['size_qty'])) {
                if ($cart->items[$cartid["id"] . $size . $color . str_replace(str_split(' ,'), '', $values)]['qty'] > $cart->items[$cartid["id"] . $size . $color . str_replace(str_split(' ,'), '', $values)]['size_qty']) {
                    return redirect()->route('front.cart')->with('unsuccess', $lang->out_stock);
                }
            }

            $cart->totalPrice = 0;
            foreach ($cart->items as $data)
                $cart->totalPrice += $data['price'];

            $myCart = $cart;
//              array_push($myCart,$cart);
        }






        $gs = Generalsetting::findOrFail(1);
        $curr = Currency::first();
        $order = new Order;
        $item_name = $gs->title." Order";
        $item_number = Str::random(4).time();
        $order['user_id'] = $request->user_id;
        $order['cart'] = utf8_encode(bzcompress(serialize($myCart), 9));
        $order['totalQty'] = $totalQuanty;
        $order['pay_amount'] = round($request->total / $curr->value, 2);
            $order['method'] = $request->method;
        $order['shipping'] = $request->shipping;
        $order['pickup_location'] = $request->pickup_location;
        $order['customer_email'] = $request->email;
        $order['customer_name'] = $request->name;
        $order['shipping_cost'] = $request->shipping_cost;
        $order['packing_cost'] = $request->packing_cost;
        $order['tax'] = $request->tax;
        $order['customer_phone'] = $request->phone;
        $order['order_number'] = Str::random(4).time();
        $order['customer_address'] = $request->address;
        $order['customer_country'] = $request->customer_country;
        $order['customer_city'] = $request->city;
        $order['customer_zip'] = $request->zip;
        $order['shipping_email'] = $request->shipping_email;
        $order['shipping_name'] = $request->shipping_name;
        $order['shipping_phone'] = $request->shipping_phone;
        $order['net_cost'] = $request->net_cost;
        $order['shipping_address'] = $request->shipping_address;
        $order['shipping_country'] = $request->shipping_country;
        $order['shipping_city'] = $request->shipping_city;
        $order['shipping_zip'] = $request->shipping_zip;
        $order['order_note'] = $request->order_notes;
        $order['coupon_code'] = $request->coupon_code;
        $order['coupon_discount'] = $request->coupon_discount;
        $order['dp'] = $request->dp;
        $order['payment_status'] = "Pending";
        $order['currency_sign'] = $curr->sign;
        $order['currency_value'] = $curr->value;
        $order['vendor_shipping_id'] = $request->vendor_shipping_id;
        $order['vendor_packing_id'] = $request->vendor_packing_id;
        $val = $request->total / $curr->value;
        $val = $val / 100;
        $sub = $val * $gs->affilate_charge;
        $user = User::findOrFail($request->user_id);
        $user->affilate_income += $sub;
        $user->update();
        $order['affilate_user'] = $user->name;
        $order['affilate_charge'] = $sub;
        $order->save();

        $track = new OrderTrack;
        $track->title = 'Pending';
        $track->text = 'You have successfully placed your order.';
        $track->order_id = $order->id;
        $track->save();

        $notification = new Notification;
        $notification->order_id = $order->id;
        $notification->save();
        if($request->coupon_id != "")
        {
            $coupon = Coupon::findOrFail($request->coupon_id);
            $coupon->used++;
            if($coupon->times != null)
            {
                $i = (int)$coupon->times;
                $i--;
                $coupon->times = (string)$i;
            }
            $coupon->update();
        }

        if($gs->is_smtp == 1)
        {
            $data = [
                'to' => $request->email,
                'type' => "new_order",
                'cname' => $request->name,
                'oamount' => "",
                'aname' => "",
                'aemail' => "",
                'wtitle' => "",
                'onumber' => $order->order_number,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendAutoOrderMail($data,$order->id);
        }
        else
        {
            $to = $request->email;
            $subject = "Your Order Placed!!";
            $msg = "Hello ".$request->name."!\nYou have placed a new order.\nYour order number is ".$order->order_number.".Please wait for your delivery. \nThank you.";
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            mail($to,$subject,$msg,$headers);
        }
        //Sending Email To Admin
        if($gs->is_smtp == 1)
        {
            $data = [
                'to' => Pagesetting::find(1)->contact_email,
                'subject' => "New Order Recieved!!",
                'body' => "Hello Admin!<br>Your store has received a new order.<br>Order Number is ".$order->order_number.".Please login to your panel to check. <br>Thank you.",
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        }
        else
        {
            $to = Pagesetting::find(1)->contact_email;
            $subject = "New Order Recieved!!";
            $msg = "Hello Admin!\nYour store has recieved a new order.\nOrder Number is ".$order->order_number.".Please login to your panel to check. \nThank you.";
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            mail($to,$subject,$msg,$headers);
        }

        return response()->json(['order'=>$order,"message"=>"","status"=>200],200);
    }// end fun
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetCoupon(Request $request){
        $get = Coupon::where('code',$request->code)
            ->first();
        return response()->json(['data'=>$get,'message'=>"","status"=>200]);
    }//end fun
    public function GetOrders(Request $request){
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }

        $orders = Order::where('user_id',$request->user_id)
        ->orderBy('id','DESC')
        ->get();

        return response()->json(["data"=>$orders,"message"=>"","status"=>200]);

    }//end fun
    public function OneOrder(Request $request){
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }

        $order = Order::where('id',$request->order_id)
            ->with('track')->with('vendor')->first();
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));


        $product = [];
        $product_ids = [];

        foreach ($cart->items as $oneProduct){

            $current_item = $oneProduct['item'];

            if (isset($current_item->id) && in_array($current_item->id,$product_ids) ){
               // dd( $product[$current_item->id]);

               // $current_item->product_count += 1;
            }else if(isset($current_item->id)){
                $current_item->product_counts =$oneProduct['qty'];
                array_push($product_ids,$current_item->id);
                array_push($product,$current_item);
//                $product[$current_item->id]=collect($current_item);
            }

        }

//
//        $collect = collect($product);
//
//
//        return $collect;

        $order->new_cart = $product;
        //  var_dump($cart);
        return response()->json(["data"=>$order,"message"=>"","status"=>200]);
    }//end fun
}
