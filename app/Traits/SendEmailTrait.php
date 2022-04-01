<?php

namespace App\Traits;

use App\Models\DoctorModels\Doctor;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\SendMail;
use Carbon\Carbon;
use Illuminate\Support\Str;


trait SendEmailTrait
{
    public function sendMail($email){
        //$token = $this->generateToken($email);
        //return response('we are in sendmail funciton');
        $code = $this->generateCode($email);
        Mail::to($email)->send(new sendMail($code));
    }

    public function validEmail($email) {
       return !!Doctor::where('email', $email)->first();
    }

    public function generateCode($email){
        $isOtherCode = DB::table('code_reset')->where('email', $email)->first();

      if($isOtherCode) {
        return $isOtherCode->code;
      }

      $code = Str::random(6);;
      $this->storeCode($code, $email);
      return $code;
    }

    public function storeCode($code, $email){
        DB::table('code_reset')->insert([
            'email' => $email,
            'code' => $code,
            'created_at' => Carbon::now()
        ]);
    }

     // public function generateToken($email){
    //   $isOtherToken = DB::table('password_resets')->where('email', $email)->first();

    //   if($isOtherToken) {
    //     return $isOtherToken->token;
    //   }

    //   $token = Str::random(80);;
    //   $this->storeToken($token, $email);
    //   return $token;
    // }

    // public function storeToken($token, $email){
    //     DB::table('password_resets')->insert([
    //         'email' => $email,
    //         'token' => $token,
    //         'created_at' => Carbon::now()
    //     ]);
    // }

}
