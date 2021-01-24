<?php
require_once("Class.php");
header('Content-Type: application/json');

$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$mailgun_api_key = $_ENV['MAILGUN_API_KEY'];
$mailgun_domain = $_ENV['MAILGUN_DOMAIN'];

$date_scheduled = $_POST["date_scheduled"];
$time_scheduled = $_POST["time_scheduled"];

$database = new Database();
$db = $database->getConnection($db_host, $db_name, $db_user, $db_pass);
$eni = new myClass($db);

//send mail
    if (!empty($date_scheduled) && !empty($time_scheduled)) {
        $date_scheduled = date("d-M-Y", strtotime($date_scheduled));
        $time_scheduled = date("h:i a", strtotime($time_scheduled));

        if ($eni->sendMail($date_scheduled, $time_scheduled, $mailgun_api_key, $mailgun_domain)) {
            // $message_array =  ['status'=>1, 'msg'=>"Success"];
            echo json_encode(['status'=>1, 'msg'=>"Success"]);
        } else {
            // $message_array =  ;
            echo json_encode(['status'=>0, 'msg'=>"Email not sent"]);
        }
    }
?>