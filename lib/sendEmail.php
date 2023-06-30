<?php

    use PHPMailer\PHPMailer\PHPMailer;

    require_once 'PHPMailer/Exception.php';
    require_once 'PHPMailer/PHPMailer.php';
    require_once 'PHPMailer/SMTP.php'; 
   
    
    
    /*$ip = $_SERVER['REMOTE_ADDR'];
    $captcha = $dataArray['captcha'];
    $secretKey = "6LcjuQ4gAAAAABROHx9QQ_DnXigr033wbkEIZWqH";
    
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha&remoteip=$ip");
    
    $attr = json_decode($response,TRUE);*/
    
    /*if($attr['success'])
    {*/
    
    if(isset($_POST['name']) && isset($_POST['message'])){
        if($_POST['name'] != '' && $_POST['message'] != ''){
            
            $dataArray = json_decode($_POST['data'],true);
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
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                // $mail->Host       = "ssl://smtp.gmail.com";
                $mail->Host       = 'mail.d9taskers.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'consultas@d9taskers.com';
                $mail->Password   = 'consultas1.';
                $mail->SMTPSecure = 'ssl'; 
                $mail->Port       = 465;
                                                    
            
                //Recipients
                $mail->setFrom('consultas@d9taskers.com');
                $mail->addAddress('consultas@d9taskers.com');
            
                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Enviado desde pagina Web TASKERS' ;
                
                $body = '<strong> Ha recibido un mensaje de: </strong> <br> <p>'.$name.'</p>';
                $body .= '<strong> correo electronico: </strong> <br> <p>'.$email.'</p>';
        
                if(!empty($phone)){
                    $body .= "<strong> con telefono: </strong> <br> <p> $phone \n </p>";
                }
                if(!empty($company)){
                    $body .= "<strong> de la empresa: </strong> <br> <p> $company \n </p>";
                }
        
                $body .= '<strong> mensaje: </strong> <br> <p> '.$message;
        
                $body .= '</div>';
    
                $mail->Body = $body;
            
                $mail->send();
        
                $sended = array('option' => 'succeed');
                echo json_encode($sended);
        
            } catch (Exception $e) {
                $sended = array('option' => 'failed','error2' => $mail->ErrorInfo);
                echo json_encode($sended);
            }
        }
    }
    else{
        $sended = array('option' => 'failed','error3' => $mail->ErrorInfo);
                echo json_encode($sended);
    }
    
    /*}
    else {
        $sended = array('option' => 'failed-captcha','error2' => "VERIFICAR CAPTCHA");
        echo json_encode($sended);
    }*/