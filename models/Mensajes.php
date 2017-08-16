<?php
namespace app\models;

use Yii;

class Mensajes{
    
    public function mandarMensage($mensaje, $numeroDestino){
        /**  ENVIO DE UN SOLO MENSAJE  **/
        curl_setopt_array($ch = curl_init(), array(
            CURLOPT_URL => "http://smsmasivos.com.mx/sms/api.envio.new.php",
            //HTTPS
            //CURLOPT_URL => "https://smsmasivos.com.mx/sms/api.envio.new.php",
            //CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POSTFIELDS => array(
                    "apikey" => "5889483f5736e264edca721240822687f445205f",//API KEY DE CUENTA 
                    "mensaje" => $mensaje,
                    "numcelular" => $numeroDestino,
                    "numregion" => "52"
                )
        ));
        
        $respuesta=curl_exec($ch);
        $respuestaDecode=json_decode($respuesta);
        if($respuestaDecode->estatus == "ok"){
            curl_close($ch);
            return true;            
        }else{
            return false;
        }
        //echo $respuesta->mensaje;
    }
}

?>