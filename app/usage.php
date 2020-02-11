<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class usage extends Model
{
    protected $table = 'app_usage';
    protected $fillable = ['day','useTime','location','user_id','application_id'];
    
    public function new_usage($day,$useTime,$location,$user_id,$application_id)
    {
        $usage = new usage;
        $usage->day = $day;
        $usage->useTime = $useTime;
        $usage->location = $location;
        $usage->user_id = $user_id;
        $usage->application_id = $application_id;
        $usage->save();
    }

    public function getUsage ($user_id)
    {
        $usages = DB::table('app_usage')->select('user_id','application_id','day', DB::raw("SUM(useTime) as totalTime"))
                                        ->from('app_usage')
                                        ->where('user_id', $user_id)
                                        ->groupBy('application_id','user_id','day')
                                        ->get();
        return $usages;
    }

     public function getLocationUsage ($user_id)
    {
        $usages = DB::table('app_usage')->select('user_id','application_id','day', 'location', DB::raw("SUM(useTime) as totalTime"))
                                        ->from('app_usage')
                                        ->where('user_id', $user_id)
                                        ->groupBy('application_id','user_id','day', 'location')
                                        ->get();
        return $usages;
    }

    /*
    Public function userExists($id){
        $usages = self::where('email',$id)->get();
        
        foreach ($usages as $key => $value) {
            if($value->email == $id){
                return true;
            }
        }
        return false;
    }
    */
}
