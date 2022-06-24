<?php

namespace App\Core;

use App\Model\User as UserModel;

class Security
{

    public function isConnected()
    {
        if(!empty($_SESSION['user']['id']) && !empty($_SESSION['user']['token'])){
            $user = new UserModel();
            $response = $user->verifyToken($_SESSION['user']['id'], $_SESSION['user']['token']);
            if($response == true){
                return true;
            }
        }
        header('Location:login');
        die();
    }

    public function isAuthor()
    {
        self::isConnected();
        if($_SESSION['user']['role_id'] == 2){
            return true;
        }
        http_response_code(403);
        die();
    }

    public function isAdmin()
    {
        self::isConnected();
        if($_SESSION['user']['role_id'] >= 3){
            return true;
        }
        http_response_code(403);
        die();
    }


    public static function login($user=null): void
    {
        $user->save();
        $_SESSION['user'] =
            [
                'id' => $user->getId(),
                'token' => $user->getToken(),
                'email' => $user->getEmail(),
                'role_id' => $user->getRoleId(),
            ];
    }

}
