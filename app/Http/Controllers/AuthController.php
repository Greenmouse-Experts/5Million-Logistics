<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\ResetPasswordCode;
use App\Mail\VerificationCode;
use App\Models\Referee;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register user.
     *
     * @return json
     */
    public function register(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'account_type' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:255'],
            'agreement' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        if($request->referrer_code == null)
        {
            $user = User::create([
                'account_type' => $request->account_type,
                'referral_code' => $this->referrer_id_generate(7),
                'first_name' => ucfirst($request->first_name),
                'last_name' => ucfirst($request->last_name),
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'agreement' => $request->agreement,
                'password' => Hash::make($request->password)
            ]);
        } else {
            $validator = Validator::make(request()->all(), [
                'referrer_code' => 'exists:users,referral_code'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $referrerId = User::where('referral_code', $request->referrer_code)->first();

            $user = User::create([
                'account_type' => $request->account_type,
                'referral_code' => $this->referrer_id_generate(7),
                'first_name' => ucfirst($request->first_name),
                'last_name' => ucfirst($request->last_name),
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'agreement' => $request->agreement,
                'password' => Hash::make($request->password),
                'referrer_id' => $referrerId->id
            ]);

            Referee::create([
                'referee_id' => $user->id,
                'referrer_id' => $referrerId->id,
            ]);
        }

        $code = mt_rand(1000, 9999);

        $user->update([
            'code' => $code
        ]);

        // Send verification code to user
        Mail::to($user->email)->send(new VerificationCode($user->code));

        return response()->json([
            'success' => true,
            'message' => 'Registration Successfully, Please check your email for a verification code',
            'data' => $user
        ]);
    }

    function referrer_id_generate($input, $strength = 7) 
    {
        $input = '0123456789';
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
    
        return $random_string;
    }
    
    public function registerConfirm(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'code' => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $user = User::where('code', $request->code)->first();

        if($user == null)
        {
            return response()->json([
                'success' => false,
                'message' => 'This activation code is invalid.'
            ]);
        }

        if($user->code == $request->code)
        {
            $user->email_verified_at = now();
            $user->code = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User verified successfully, you can now login.',
                'data' => null
            ]);
        }
        
    }

    public function email_verify_resend($email)
    {
        $user = User::where('email', $email)->first();

        if(!$user)
        {
            return response()->json([
                'success' => false,
                'message' => "Email doesn't exist!",
            ]);
        }

        if (!$user->email_verified_at) 
        {
            $code = mt_rand(1000, 9999);

            $user->update([
                'code' => $code
            ]);

            // Send verification code to user
            Mail::to($user->email)->send(new VerificationCode($user->code));

            return response()->json([
                'success' => true,
                'message' => 'A fresh verification code has been sent to your email address.',
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'User email has been verified, You can now login.',
            ]);
        }
    }

    /**
     * Login user.
     *
     * @return json
     */
    public function login(Request $request)
    {
        $input = $request->only(['email', 'password']);

        $validate_data = [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($input, $validate_data);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $user = User::query()->where('email', $request->email)->first();

        if ($user && !Hash::check($request->password, $user->password)){
            return response()->json([
                'success' => false,
                'message' => 'Incorrect Password!',
            ]);
        }

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email does\'nt exist',
            ]);
        }

        if($user->account_type == 'Administrator'){
            return response()->json([
                'success' => false,
                'message' => 'You are not a User.'
            ]);
        }

        // authentication attempt
        if (auth()->attempt($input)) {    
            if($user->status !== 'Active'){
                return response()->json([
                    'success' => false,
                    'message' => 'Account Deactivated, please contact the site administrator!',
                ]);
            }

            if(!$user->email_verified_at){
                
                $code = mt_rand(1000, 9999);

                $user->update([
                    'code' => $code
                ]);

                // Send verification code to user
                Mail::to($user->email)->send(new VerificationCode($user->code));

                return response()->json([
                    'success' => false,
                    'message' => 'Before proceeding, please check your email for a verification link',
                    'data' => $user
                ]);
            }
            
            $token = auth()->user()->createToken('passport_token')->accessToken;
            
            return response()->json([
                'success' => true,
                'message' => 'User login succesfully, Use token to authenticate.',
                'token' => $token,
                'data' => new UserResource(Auth::user())
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ]);
        }
    }

    public function forget_password(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();

        // Generate random code
        $data['code'] = mt_rand(1000, 9999);

        // Create a new code
        $codeData = ResetCodePassword::create($data);

        // Send email to user
        Mail::to($request->email)->send(new ResetPasswordCode($codeData->code));

        return response()->json([
            'success' => true,
            'message' => 'We have emailed your password reset code!'
        ]);
    }

    public function code_check(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response()->json([
                'success' => false,
                'message' => 'Password reset code expired'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password reset code valid',
            'data' => $passwordReset->code,
        ]);
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (ResetCodePassword::where('code', '=', $request->code)->exists()) {
            // find the code
            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

            // check if it does not expired: the time is one hour
            if ($passwordReset->created_at > now()->addHour()) {
                $passwordReset->delete();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Password reset code expired'
                ]);
            }

            // find user's email 
            $user = User::firstWhere('email', $passwordReset->email);

            // update user password
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // delete current code 
            $passwordReset->delete();

            return response()->json([
                'success' => true,
                'message' => 'Password has been successfully reset'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Code does\'nt exist in our database'
            ]); 
        }
    }

    public function admin_login(Request $request)
    {
        $input = $request->only(['email', 'password']);

        $validate_data = [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($input, $validate_data);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $user = User::query()->where('email', $request->email)->first();

        if ($user && !Hash::check($request->password, $user->password)){
            return response()->json([
                'success' => false,
                'message' => 'Incorrect Password!',
            ]);
        }

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => "Email doesn't exist",
            ]);
        }

        // authentication attempt
        if (auth()->attempt($input)) {
            if($user->account_type == 'Administrator'){
                $token = auth()->user()->createToken('passport_token')->accessToken;
            
                return response()->json([
                    'success' => true,
                    'message' => 'Admin login succesfully, Use token to authenticate.',
                    'token' => $token,
                    'data' => Auth::user()
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'You are not an Administrator!.'
            ]);
                    
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ]);
        }
    }

    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect('/admin/login');
    }
}
