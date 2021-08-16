<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Classes\GeniusMailer;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Counter;
use App\Models\Pagesetting;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;
use Markury\MarkuryPost;



use App\jwtClass\AUTHORIZATION;

use App\Models\Firebase_token;

class AuthController extends Controller
{

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191|string',
            'password' => 'required',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }
        //----------------------------------
        $resultJson = ["data" => null, "message" => "", "status" => ""];
        $validator = Validator::make($request->all(), ['phone' => 'required|unique:users'], []);
        if ($validator->fails()) {
            $resultJson["message"] = "the phone has been aready token";
            $resultJson["status"] = 409;
            return response()->json($resultJson, 200);
        }
        //---------------------------------
        $validator = Validator::make($request->all(), ['email' => 'nullable|unique:users'], []);
        if ($validator->fails()) {
            $resultJson["message"] = "the email has been aready token";
            $resultJson["status"] = 406;
            return response()->json($resultJson, 200);
        }
        //----------------------------------
        $data = $request->all();
        $data['password'] = bcrypt($request['password']);
        $data['email_verified'] = "Yes";
        $user_id = User::create($data);
        $user_info = User::find($user_id->id);
        $user_info->token = AUTHORIZATION::generateToken($user_info);
        $resultJson["data"] = $user_info;
        $resultJson["status"] = 200;
        return response()->json($resultJson, 200);
    }

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            /*   'phone_code'   => 'required',*/
            'phone' => 'required',
            'password' => 'required',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1), 'code' => 422], 422);
        }
        $resultJson = ["data" => null, "message" => "", "status" => ""];
        //---------------------------------------------
        $credentials = ["phone"=> $request->phone , "password"=>$request->password] ;
        if(!$token =  Auth::attempt($credentials)){
            $resultJson["message"] = "user not found";
            $resultJson["status"] = 404;
            return response()->json($resultJson, 200);
        }
        $user_info  = auth()->user();
        $user_info->token = AUTHORIZATION::generateToken($user_info);
        $resultJson["data"] = $user_info;
        $resultJson["status"] = 200;
        return response()->json($resultJson, 200);

    }

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */
    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'phone_token' => 'required',
            'software_type' => ['required', Rule::in(['ios', 'android'])],
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1), 'code' => 422], 422);
        }
        $where = ['user_id' => $request->user_id,
                  'phone_token' => $request->phone_token,
                  'software_type' => $request->software_type];
        Firebase_token::where($where)->delete();
        $resultJson = ["data" => null, "message" => "success logout", "status" => 200];
        return response()->json($resultJson, 200);
    }

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */
    public function getProfile(Request $request)
    {

        $user_id = (isset($request->user_id)) ? $request->user_id : 0;
        $user = User::where('id', $user_id)->first();
        $resultJson = ["data" => null, "message" => "", "status" => ""];
        if (isset($user->id)) {
            $user->token = AUTHORIZATION::generateToken($user);
            $resultJson["data"] = $user;
            $resultJson["status"] = 200;
            return response()->json($resultJson, 200);
        }
        $resultJson["message"] = "user id not fund";
        $resultJson["status"] = 404;
        return response()->json($resultJson, 200);
    }

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */
    public function updateFirebase(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'phone_token' => 'required',
            'software_type' => ['required', Rule::in(['ios', 'android'])],
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1), 'code' => 422], 422);
        }

        $resultJson = ["data" => null, "message" => "", "status" => ""];
        $arr = ['user_id' => $request->user_id, 'phone_token' => $request->phone_token,
            'software_type' => $request->software_type];
        Firebase_token::updateOrCreate($arr, $arr);


        $tokens = Firebase_token::where("user_id", $request->user_id)->where("software_type", $request->software_type)->get();
        $resultJson["data"] = $tokens;
        $resultJson["status"] = 200;
        return response()->json($resultJson, 200);
    }

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'date_of_birth' => 'nullable|numeric',
                'receive_notifications' => 'nullable|in:yes,no',
                'gender' => 'nullable|in:male,female',
            ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1), 'code' => 422], 422);
        }
        $user = $request->userData;
        //==============================================
        $Udata = [];
        //---------------------
        if (isset($request->photo)) {
            if ($file = $request->file('photo'))
            {
                $name = time().$file->getClientOriginalName();
                $file->move('assets/images/user',$name);
                if($user->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/user/'.$user->photo)) {
                        unlink(public_path().'/assets/images/user/'.$user->photo);
                    }
                }
                $Udata['photo'] = $name;
            }
        }
        //---------------------
        if (isset($request->name)) {
            $Udata['name'] = $request->name;
        }
        //---------------------
        if (isset($request->latitude)) {
            $Udata['latitude'] = $request->latitude;
        }
        //---------------------
        if (isset($request->longitude)) {
            $Udata['longitude'] = $request->longitude;
        }
        //---------------------
        if (isset($request->receive_notifications)) {
            $Udata['receive_notifications'] = $request->receive_notifications;
        }
        //---------------------
        if (isset($request->gender)) {
            $Udata['gender'] = $request->gender;
            /*  $valid = (in_array($request->gender,['male', 'female']))? true:false;
              if ($valid == false) {
                  return response()->json(['gender' => "The gender in ( male , female) "], 422);
              }*/
        }
        //---------------------
        if (isset($request->date_of_birth)) {
            $Udata['date_of_birth'] = $request->date_of_birth;
        }
        //---------------------
        if (isset($request->phone_code)) {
            $Udata['phone_code'] = $request->phone_code;
        }
        //---------------------
        if (isset($request->phone)) {
            $Udata['phone'] = $request->phone;
            $valid = User::where('id', "!=", $request->user_id)->where('phone', $request->phone)->first();
            if (isset($valid->id)) {
                return response()->json(['error' => "The phone has already been taken."], 409);
            }
        }
        //---------------------
        if (isset($request->email)) {
            $Udata['email'] = $request->email;
            $valid = User::where('id', "!=", $request->user_id)->where('email', $request->email)->first();
            if (isset($valid->id)) {
                return response()->json(['error' => "The email has already been taken."], 406);
            }
        }
        //==============================================
        if (!empty($Udata)) {
            User::where('id', $request->user_id)->update($Udata);
        }
        $user_info = User::find($request->user_id);
        $user_info->token = AUTHORIZATION::generateToken($user_info);

        $resultJson = ["data" => $user_info, "message" => "", "status" => "200"];

        return response()->json($resultJson, 200);
    }

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */
    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'phone' => 'required',
            ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1), 'code' => 422], 422);
        }
        User::where("phone", $request->phone)->delete();
        return response()->json(["msg" => "success delete"], 200);
    }

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */
    public function myAddress(Request $request){
        $user = ($request->user_id)? $request->user_id:0;
        $address = UserAddress::where("user_id",$user)->get();
        $resultJson["data"] = $address;
        $resultJson["message"] = "my address";
        $resultJson["status"] = 200;
        return response()->json($resultJson, 200);
    }

    public function addAddress(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'nullable',
            'phone' => 'required',
            'other_phone' => 'nullable',
            'address' => 'required',
            'google_lat' => 'required',
            'google_long' => 'required',
            'type' => 'required|in:home,work',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }
        //-----------------------------------------------
        $resultJson = ["data" => null, "message" => "", "status" => 200];
        $data = $request->all();
        $address = UserAddress::create($data);
        $resultJson["data"] = $address;
        $resultJson["message"] = "success add address";
        return response()->json($resultJson, 200);
    }

    public function editAddress(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:user_address,id',
            'name' => 'nullable',
            'phone' => 'nullable',
            'other_phone' => 'nullable',
            'address' => 'nullable',
            'city' => 'nullable',
            'google_lat' => 'nullable',
            'google_long' => 'nullable',
            'type' => 'nullable|in:home,work',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }
        //-----------------------------------------------
        $resultJson = ["data" => null, "message" => "", "status" => 200];
        $data = $request->all();
        unset($data["id"]);
        UserAddress::where("id",$request->id)->update($data);
        $address = UserAddress::where("id",$request->id)->first();
        $resultJson["data"] = $address;
        $resultJson["message"] = "success edit address";
        return response()->json($resultJson, 200);
    }

    public function deleteAddress(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:user_address,id',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }
        //-----------------------------------------------
        $resultJson = ["data" => null, "message" => "seccess delete address", "status" => 200];
        UserAddress::where("id",$request->id)->delete();
        return response()->json($resultJson, 200);
    }//end fun
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function StoreComment(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'text' => 'required',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }
        $new = new Comment();
        $new->user_id = $request->user_id;
        $new->product_id = $request->product_id;
        $new->text = $request->text;
        $new->save();
        $json = Comment::where("id",$new->id)->with('user')->first();
        $resultJson = ["data" => $json, "message" => "success add comment ", "status" =>200];
        return response()->json($resultJson,200);

    }//end fun
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function StoreContact(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'text' => 'required',
        ], []);
        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten(1)], 422);
        }
        $gs = Generalsetting::findOrFail(1);
        $ps =  Pagesetting::where('id','=',1)->first();
        if($gs->is_capcha == 1)
        {
            // Capcha Check
            $value = session('captcha_string');
            if ($request->codes != $value){
                return response()->json(array('errors' => [ 0 => 'Please enter Correct Capcha Code.' ]));
            }
        }
        // Login Section
        $ps = DB::table('pagesettings')->where('id','=',1)->first();
        $subject = "Email From Of ".$request->name;
        $to = $ps->contact_email != null ?$ps->contact_email : 'support@athomegy.com';
        $name = $request->name;
        $phone = $request->phone;
        $from = $request->email;
        $msg = "Name: ".$name."\nEmail: ".$from."\nPhone: ".$phone."\nMessage: ".$request->text;
        if($gs->is_smtp)
        {
            $data = [
                'to' => $to,
                'subject' => $subject,
                'body' => $msg,
            ];
            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        }
        else
        {
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            mail($to,$subject,$msg,$headers);
        }

        return response()->json('done',200);
    }//end fun




    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */


}// end class
