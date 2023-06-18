<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;

class SignupController extends Controller
{
    public function signup(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password'=> 'required|string|min:8',
                'confirm_password' => 'required|same:password',
                'phone_number'=>'required|unique:users'
            ]);

            if ($validator->fails()){
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response,400);
            }
            $input = $request->all();   
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);

            $success['token'] = $user->createToken('247Assignment')->plainTextToken;
            $success['name'] = $user->name;

            $response = [
                'success' => true,
                'data' => $success,
                'message'=> 'Hello '.$user->name.', registration has been successfully'
            ];
            return response()->json($response, 200);
        }catch(\Throwable $th) {
            return response()->json([
                'status' => false,
                "message"=>"Error",
                "error"=>"An unexpected error occurred while signup."                
            ],500);
        }
    }

    public function login(Request $request){
        try{
            if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
                $user = Auth::user();
                $success['token'] = $user->createToken('247Assignment')->plainTextToken;
                $success['name'] = $user->name;

                $response = [
                    'success' => true,
                    'data' => $success,
                    'message'=> 'User has been login successfully'
                ];
                return response()->json($response, 200); 
            }
            return response()->json(['message' => 'Wrong email or password'], 401);
        }catch(\Throwable $th) {
            return response()->json([
                'status' => false,
                "message"=>"Error",
                "error"=>"An unexpected error occurred while login."                
            ],500);
        }
    }

    public function update(Request $request,$id)
    {
        try{
            $user=User::find($id);
            if($user){
                $user->update($request->all());
                $response = [
                    'success' => true,
                    'data' => $user,
                    'message' => $user->name. ' your profile data has been updated successfully'
                ];
                
                return response()->json($response,200);
            }else{
                $response = [
                    'success' => false,
                    'message' => "User hasn't registered yet"
                ];
                return response()->json($response,400);
            }
        }catch(\Throwable) {
            return response()->json([
                'status' => false,
                "message"=>"Error",
                "error"=>"An unexpected error occurred while updating the resource."
                
            ],500);
        }
        

    }

    public function delete(Request $request,$id)
    {
        try{
            $user=User::find($id);
            if($user){
                $user->delete($request->all());
                $response = [
                    'success' => true,
                    'message' => $user->name .' your account has been deleted'
                ];
                return response()->json($response,200);
            }else{
                $response = [
                    'success' => false,
                    'message' => "User hasn't registered yet"
                ];
                return response()->json($response,400);
            }
        }catch(\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th-getMassage()
            ],500);
        }   
    }

    public function logout(Request $request){
        try{
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message'=> 'User has been logged out successfully '
            ],200);
        }catch(\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th-getMassage()
            ],500);
        }
    }
}
