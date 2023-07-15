<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminResetPasswordEmail;
use App\Models\Admin;
use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $validator = Validator($request->all() , [
            'email'=>'required|string|exists:admins,email', // exists -  لانه موجود مش فريد زي الي تحت جاي يسجل اما هاد جاي وبياناته نوجودة
            'password'=>'required', // وهان ما حطينا رولز لانه بياناته موجودة ضمنياً
        ]);
        if (! $validator->fails()) {
            $admin = Admin::where('email' , '=' , $request->input('email'))->first();
            if(Hash::check($request->input('password') ,$admin->password)){
                $token = $admin->createToken('abi-token');//token مميزة للمستحدم المسجل دخول -- 'token'=>$token رجع اوبجيكت للي تخزن في الداتا بيز مع متغير التويكن
                $admin->token = $token->accessToken; //خزنلي في اوبجكت الادمن هاد الحقل
                return new response(['status'=>true , 'message'=>'Login Successfully' ,'token'=>$admin]  , Response::HTTP_OK);
            }else{
            return new Response(['status'=>false , 'message'=>'Failed to Login , Password wrong'] , Response::HTTP_BAD_REQUEST);
            }
        }else{
            return new Response(['status'=>false , 'message'=>$validator->getMessageBag()->first()] , Response::HTTP_BAD_REQUEST);
        }
    }

    //
     public function logout( Request $request)
     {
         $user = $request->user('admins-api');
         $revoked = $user->token()->revoke();
         return Response(['status'=>$revoked, 'message'=>$revoked ? 'Logout Successfully' : 'Failed to Logout']);
     }
     //



    //
    public function register(Request $request)
    {
        $validator = Validator($request->all() , [
            'name'=>'required|string|min:3|max:20',
            'email'=>'required|string|unique:admins,email',
            'password'=>['required' , Password::min(3)->letters()->uncompromised()->symbols()->mixedCase()],
        ]);
        if (!$validator->fails()) {
            $admin = new Admin();
            $admin->name = $request->input('name');
            $admin->email = $request->input('email');
            $admin->password = Hash::make($request->input('password'));
            $saved = $admin->save();
            if($admin->save()){
                $role = Role::findById(1);
                $admin->assignRole($role);
            }
            return response(['status'=>$saved , 'message'=>$saved ? 'Register Successfully' : 'Register Failed'] , $saved ?  Response::HTTP_OK : Response::HTTP_BAD_REQUEST );
        }else{
            return Response()->Json(['status'=>false , 'message'=>$validator->getMessageBag()->first()] , Response::HTTP_BAD_REQUEST);
        }
    }


    //
    public function changePassword(Request $request)
    {
                /**
                 * Must sent token in header ,
                 * Must sent following data in body :
                    * 1- Old password ,
                    * 2- New password ,
                    * 3- New password confirmation,
                 */

                 $validator = validator($request->all(),
                 [
                    'current_password'=>'required|current_password:admin-api',
                    'new_password'=>['required', 'confirmed' , Password::min(3)->letters()->uncompromised()->symbols()->mixedCase()],
                    'new_password_confirmation'=>'required',
                 ]);

                 if(! $validator->fails())
                 {
                    $user = $request->user('admin-api');
                    $user->password = Hash::make($request->input('new_password'));
                    $saved = $user->save();
                        return response(['status'=>$saved , 'message'=>$saved ? 'change password Successfully' : 'change password failed'] , $saved ?  Response::HTTP_OK : Response::HTTP_BAD_REQUEST );
                 }else{
                        return Response()->Json(['status'=>false , 'message'=>$validator->getMessageBag()->first()] , Response::HTTP_BAD_REQUEST);
                }
    }

    public function forgetPassword(Request $request)
    {

        $validator = validator($request->all(),
        [
           'email'=>'required|email|exists:admins,email',
        ]);

        if(! $validator->fails())
        {
          // بدنا نجيب المستخدم بناء على الايميل
          // لما الايميل المدخل يتطابق اعطيني اوبجكت فيه بيانات المستخدم كاملة

           $user = Admin::where('email', '=', $request->input('email'))->first();
           if(is_null($user->reset_code))
           {
            $randomCode = random_int(1000 , 4000);
            $user->reset_code = Hash::make($randomCode);
            $saved = $user->save();
            Mail::to($user)->send(new AdminResetPasswordEmail($user,  $randomCode));
               return new Response(['status'=>$saved , 'data'=>$user] , $saved ?  Response::HTTP_OK : Response::HTTP_BAD_REQUEST );
           }else{
            return new Response(['status'=>false , 'message'=>'the reset code is entered'] , Response::HTTP_BAD_REQUEST);
           }

        }else{
               return new Response(['status'=>false , 'message'=>$validator->getMessageBag()->first()] , Response::HTTP_BAD_REQUEST);
       }
    }
    public function resetPassword(Request $request)
    {
        $validator = validator($request->all(),
        [
           'email'=>'required|email|exists:admins,email',
           'reset_code'=>'required|numeric|digits:4',
           'new_password'=>['required','confirmed' , Password::min(3)->letters()->uncompromised()->symbols()->mixedCase()],
           'new_password_confirmation'=>'required'
        ]);

        if(! $validator->fails())
        {
            $admin = Admin::where('email','=',$request->input('email'))->first();
            if(! is_null($admin->reset_code))
            {
                if(Hash::check($request->input('reset_code') ,$admin->reset_code ))
                {
                    $admin->password = Hash::make($request->input('new_password'));
                    $admin->reset_code = null;
                    $saved = $admin->save();
                }else{
                    return new Response(['status'=>false , 'message'=>'your reset code is not matches !! '] , Response::HTTP_BAD_REQUEST);
                }
                    return new Response(['status'=>true  , 'message'=>'your password is changed successfully'], Response::HTTP_OK );
            }else{
                    return new Response(['status'=>false , 'message'=>'the reset code is unavailable please resend the reset code !!'] , Response::HTTP_BAD_REQUEST);

            }
                    return new Response(['status'=>true   , 'data'=>$admin ,'message'=>'DONE:)'],  Response::HTTP_OK  );

        }else{
                    return new Response(['status'=>false  , 'message'=>$validator->getMessageBag()->first()] , Response::HTTP_BAD_REQUEST);

        }
    }
}
