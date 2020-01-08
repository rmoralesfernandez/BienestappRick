<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class User extends Model
{
    
	protected $table = 'users';
    protected $fillable = ['name','password','email'];
    
    public function register(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->password = encrypt($request->password);
        $user->email = $request->email;
        $user->save();
    }
    
    Public function userExists($email){
        $users = self::where('email',$email)->get();
        
        foreach ($users as $key => $value) {
            if($value->email == $email){
                return true;
            }
        }
        return false;
    }
}
