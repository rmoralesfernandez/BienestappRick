<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Helper\Token;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $user = new User();
        if (!$user->userExists($request->email)){
            $user->register($request);
            $data_token = [
                "email" => $user->email,
            ];
            $token = new Token($data_token);
            
            $tokenEncoded = $token->encode();
            return response()->json([
                "token" => $tokenEncoded
            ], 201);
        }else{
            return response()->json(["Error" => "No se pueden crear usuarios con el mismo Email o con el Email vacío"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(Request $request){
        $data_token = ['email'=>$request->email];
        
        $user = User::where($data_token)->first();  
       
        if ($user!=null) {       
            if($request->password == $user->password)
            {       
                $token = new Token($data_token);
                $tokenEncoded = $token->encode();
                return response()->json(["token" => $tokenEncoded], 201);
            }   
        }     
        return response()->json(["Error" => "No se ha encontrado"], 401);
    }

    public function recoverPassword (Request $request){

        $user = User::where('email',$request->email)->first();  
        if (isset($user)) {   
            $newPassword = self::randomPassword();
            self::sendEmail($user->email,$newPassword);
            
                $user->password = $newPassword;
                $user->update();
            
            return response()->json(["Success" => "Se ha restablecido su contraseña, revise su correo electronico."]);
        }else{
            return response()->json(["Error" => "El Email no existe"]);
        }

    }


    public function sendEmail($email,$newPassword){
        $para      = $email;
        $titulo    = 'Recuperar contraseña de Bienestapp';
        $mensaje   = 'Se ha establecido "'.$newPassword.'" como su nueva contraseña.';
        $cabeceras = 'From: victor@cev.com' . "\r\n" .
                     'Reply-To: $email' . "\r\n" .
                     'X-Mailer: PHP/' . phpversion();

        //print($mensaje);
        mail($para, $titulo, $mensaje, $cabeceras);
    }


    public function randomPassword() {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
