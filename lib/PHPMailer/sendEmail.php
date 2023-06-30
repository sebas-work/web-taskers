<?php

    use PHPMailer\PHPMailer\PHPMailer;

    require_once 'lib/PHPMailer/Exception.php';
    require_once 'lib/PHPMailer/PHPMailer.php';
    require_once 'lib/PHPMailer/SMTP.php'; 
    
    $dataArray = json_decode($_POST['data'],true);
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $captcha = $dataArray['captcha'];
    $secretKey = "6LcjuQ4gAAAAABROHx9QQ_DnXigr033wbkEIZWqH";
    
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha&remoteip=$ip");
    
    $errorMessage = "";
    
    $attr = json_decode($response,TRUE);
    
    if($attr['success'])
    {
        if (validar()) {
            $name = $dataArray['name'];
            $email = $dataArray['email'];
            $phone = (isset($dataArray['phone']))?$dataArray['phone']:'';
            $company= (isset($dataArray['company']))?$dataArray['company']:'';
            $message = $dataArray['message'];
        
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                // $mail->Host       = "ssl://smtp.gmail.com";
                $mail->Host       = 'mail.d9gestion.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'consultas@d9gestion.com';
                $mail->Password   = 'd9gestion@1.';
                $mail->SMTPSecure = 'ssl'; 
                $mail->Port       = 465;                                    
            
                //Recipients
                $mail->setFrom('consultas@d9gestion.com');
                $mail->addAddress('consultas@d9gestion.com');
            
                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Enviado desde pagina Web D9' ;
        
                $body = 'Ha recibido un mensaje del usuario: <b>'.$name.'</b> <br>';
                $body .= 'con el correo electr贸nico: <b>'.$email.'</b> <br>';
        
                if(!empty($phone)){
                    $body .= "con tel茅fono: $phone \n <br>";
                }
                if(!empty($company)){
                    $body .= "de la empresa: $company \n <br>";
                }
        
                $body .= 'mensaje: '.$message;
        
                $mail->Body = $body;
            
                $mail->send();
        
                $sended = array('option' => 'succeed');
                echo json_encode($sended);
        
            } catch (Exception $e) {
                $sended = array('option' => 'failed','error2' => $mail->ErrorInfo);
                echo json_encode($sended);
            }
        } 
        else  {
            $sended = array('option' => 'failed-validation','errorSms' => $errorMessage);
            echo json_encode($sended);
        }
    }
    else {
        $sended = array('option' => 'failed-captcha2','atr' => $attr, 'prueba' =>'prueba2','response'=>$response);
        echo json_encode($sended);
    }
    
    
function validar(){
    $errorMessage = "";
    $bool = false;
    
    if(isValidString($name)){
        if(strlen($name) > 50){
            $errorMessage +='<p class="error">Nombre y Apellido, error en longituda</p>';
        }
    }
    else $errorMessage +='<p class="error">Nombre y Apellido, error</p>';
        
    
    if(!empty($company)){
       if(!isValidString($company))
            $errorMessage +='<p class="error">Empresa, no valida</p>';
    }
        
    if(!isValidString($message))
            $errorMessage +='<p class="error">Mensaje, solo ingresar texto</p>';
    
    if(!empty($phone)){
        if(!is_numeric($phone))
            $errorMessage +='<p class="error">Numero, error</p>';
    }    

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
         $errorMessage +='<p class="error">Email incorrecto</p>';
    }    
        
    
    if($errorMessage <> '') $bool = true;
    
    
    return $bool;
}


function isValidString($text){
        $pattern = "/^[a-zA-Z\sñáéíóúÁÉÍÓÚ]+$/";
        return preg_match($pattern, $text);
    }




