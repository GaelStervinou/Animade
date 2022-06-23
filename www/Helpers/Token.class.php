<?php

namespace App\Helpers;

class Token{

public static function RandomString($length = 65):string{
try{
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);

$randomString = '';
for($i = 0; $i < length; $i++){
$randomString .= $characters[random_int(0, $charactersLength - 1)];
}
return $randomString;
} catch (\Exception $exception) {
return '';
}
}

}

?>