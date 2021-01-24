<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__. DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php");
require_once("config.php");

use Mailgun\Mailgun;
use Mailgun\HttpClient\HttpClientConfigurator;
use Mailgun\Hydrator\NoopHydrator;

class myClass{
    private $conn;
    private $table_name = "users";

    function __construct($db){
        $this->conn = $db;  
    }

    public function getCoupon($coupon_code){
        try {
            $query = $this->conn->prepare("SELECT coupon_code, coupon_status FROM coupon_codes WHERE coupon_code = '" . $coupon_code . "'");
            $query->execute();
            return $query;
        } catch (Exception $e) {
            echo '{"error": {"text": '.$e->getMessage().'}';            
        }
    }

    public function updateCoupon($coupon_code){
        try {
            $query = $this->conn->prepare("UPDATE coupon_codes SET coupon_status = 1 WHERE coupon_code = '" . $coupon_code . "'");
            $query->execute();
            return true;
        } catch (Exception $e) {
            echo '{"error": {"text": '.$e->getMessage().'}';            
        }
    }

    public function sendMail($date_scheduled, $time_scheduled, $mailgun_api_key, $mailgun_domain){
        // // // First, instantiate the SDK with your API credentials
        $mg = Mailgun::create($mailgun_api_key);
        
        $domain = $mailgun_domain;

        $mg->messages()->send($domain, [
        'from'    => 'Jafar <waleade99@gmail.com>',
        'to'      => 'jafarolamidekale@gmail.com',
        'subject' => 'CODING CLASSES REMINDER',
        'text'    => 'This is to inform you that I would be ready for the class for the schedule below:
                                Date:' .$date_scheduled . '
                                Time: ' .$time_scheduled . ''
        ]);
        return $mg;
    }
    
}
?>