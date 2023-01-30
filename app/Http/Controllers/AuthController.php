<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function index(){
        return User::all();
    }
    public function destroy($id){
        $des = User::destroy($id);
        return $des;
    }
    public function store(Request $request){
        $fields = $request->validate([
            'password'=>'required|string',
            'username'=>'required'
        ]);
        $new_pass = Hash::make($request->password);
        $request->password = $new_pass;

        $User = User::create([

            'password'=>$new_pass,
            'username'=>$fields['username']
        ]);
        return $User;
    }
    public function signin(Request $request){
        $fields = $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);

        // check if exist
        $username = User::where('username', $fields['username'])->first();    
        if(!$username || !Hash::check($fields['password'],$username->password)){
            return Response(['message'=>'Bad Credentials'],401);
        }
        else{
            $token = $username->createToken('myapptoken')->plainTextToken;
            // $token = $request->user()->createToken($request->token_name);
            return $token;
        }
    }
    public function attempt(){
        $user = auth()->user();
        return $user;
    }
    public function signout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }
}
