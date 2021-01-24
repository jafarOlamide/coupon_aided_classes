<?php
require_once("admin/Class.php");
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];


$database = new Database();
$db = $database->getConnection($db_host, $db_name, $db_user, $db_pass);
$eni = new myClass($db); 

    try {
        if (isset($_POST["submit"])) {
            
            $coupon_code = $_POST["coupon"];
            
            $query_result = $eni->getCoupon($coupon_code);

            $result_row = $query_result->fetch(PDO::FETCH_ASSOC);

           if ($result_row > 0) {
                $db_coupon = $result_row["coupon_code"];  
                $coupon_status = $result_row["coupon_status"];  

                if ($coupon_code == $db_coupon && $coupon_status == 0) {
        
                    //change coupon code state in the db to show it has been used 
                    $eni->updateCoupon($coupon_code);
                    $message = "<h5 class='text-center' style = 'color:#fff;'>Successful</br>
                    You will be connected to zoom shortly.</h5>";

                    //REDIRECT TO ZOOM
                    header('Location: https://us04web.zoom.us/j/79630463506?pwd=M1VjSnQzclBVbitOam92aTJBbEdDUT09');

                } elseif ($coupon_code == $db_coupon && $coupon_status == 1){
                    $message = "<h5 class='text-center' style = 'color:#990011FF;'>Coupon already used</h5>";
                } else{
                    $message = "<h5 class='text-center' 'color:#990011FF;'>Coupon doesn't exist</h5>";
                }
           } else{
                $message = "<h5 class='text-center' 'color:#990011FF;'>Coupon Code does not exist</h5>";
           }           
        } 

        
    }catch (\Throwable $th) {
            $message = '{"error": {"text": '.$th->getMessage().'}';        
    }
?>
<!DOCTYPE html>
<!-- <html class="h-100"> -->
<html class="">
    <head >
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <title>Coupon Aided Classes</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script type="text/javascript" src="assets/jquery-3.5.1.js"></script>
    </head>
    <body class="h-100" style = "background:blue;">

        <nav class="navbar navbar-light justify-content-between">
        <!-- <a class="navbar-brand d-5" href="" style = "color:#fff; font-size:35px;"> Classes</a> -->
        <button class="btn my-2 my-sm-0" type="button" data-toggle="modal" data-target="#exampleModal" style = "color:blue; background:#fff; font-size:25px;">Schedule a Class</button>
        </nav>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Schedule a Class</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <!-- DESIGN -->
                <div class="container mb-2 col-sm-12">
                    <div class="ass-errorMesss" style="display: none;"></div>
                    <div class="assignSpinner" style="display: none;">
                        <i class="fa fa-spinner fa-spin" id="spinnerAss" style ="color:blue; font-size:30px;"></i>
                        <label id="assSpinnerLabel" style = "font-size:18px;"></label>
                    </div>
                </div>
            <!-- DESIGN -->
                <!-- <form action="index.php" method="POST"> -->
                <form action="index.php" method="POST">
                    <div class="form-inline justify-content-between">
                        <div class="form-group">
                            <label for="date" class="m-2">Date:</label>
                            <input type="date" class="form-control" id="date_scheduled" name = "date_scheduled">
                        </div>
                        
                        <div class="form-group">
                            <label for="time" class="m-2">Time (24hr Format):</label>
                            <input type="time" class="form-control" id="time_scheduled" name = "time_scheduled">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" name="schedule_class" id="schedule_class">Schedule Class</button>
            </div>
            </div>
        </div>
        </div>
        <!-- Modal -->

        <div class="container h-100">
        <!-- <div class="container h-100"> -->
            <div class="row h-100 justify-content-center align-items-center">
            <div class="col-md-6">
                    <div>
                        <h2 class="text-center display-block pb-4" style = "color:#fff;">Welcome!</h2>
                            <?php
                            if (empty($message)) {
                                $message = ""; 
                                echo $message;
                            } else{
                                echo $message;
                            }
                        ?>
                    </div>
                    <form  action = "index.php" method="post">
                        <div class="form-group">
                            <label for="email" style = "color:#fff; font-size:20px;">Please enter your Coupon Code.</label>
                            <input type="text" class="form-control" name="coupon">
                        </div>
                        <button type="submit" class="btn btn-primary btn-md mb-4" name="submit">Submit</button>
                    </form> 
            </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        
        <script type="text/javascript">
            $(document).ready(function(){
                $("#schedule_class").click(function(e){
                    let date_scheduled = $("#date_scheduled").val();
                    let time_scheduled = $("#time_scheduled").val();               
                    
                    e.preventDefault();

                    if (date_scheduled !== "" && time_scheduled !== "") {
                        $.ajax({
                        type: "POST",
                        url: "admin/send_mail.php",
                        data: {
                                'date_scheduled': date_scheduled,
                                'time_scheduled': time_scheduled
                                },
                        success: function(res2){  
                                    $('.assignSpinner').show();
                                    setTimeout(function(){  
                                        $('#assSpinnerLabel').html("Please Wait.....");
                                        _this.parent().remove();
                                    }, 1000);
                                    if(res2.status === 1){                    
                                        //Remove text
                                        $("#date_scheduled").val("");
                                        $("#time_scheduled").val("");
                                        
                                        setTimeout(function(){  
                                            $('#assSpinnerLabel').html("Jafar has been informed!");
                                            $('#spinnerAss').hide();
                                            _this.parent().remove();
                                            location.reload();     
                                        }, 2500);   

                                    }
                                    else{
                                        setTimeout(function(){  
                                            $('#assSpinnerLabel').html(res2.mgs);
                                            $('#spinnerAss').hide();
                                            _this.parent().remove();
                                            // location.reload();     
                                        }, 2500); 
                                        console.log(res2.msg);
                                        $('#spinnerAss').hide();
                                        // _this.parent().remove();
                                    }               
                        }
                        });
                    }
                        else{
                            //DISPLAY ERROR MESSAGE
                            $('.ass-errorMesss').css('display', 'block'); 
                            $('.ass-errorMesss').html("<h5 style='font-size:18px; font-weight:600'>Please select date and time</h5>");
                        }                       
                });
            });
        </script>
    </body>
</html>

