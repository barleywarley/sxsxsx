<?php
include_once("config.php");

$cpconn = mysqli_init();
if (!$cpconn) {
    dieWithError("We were unable to initialize PHP MySQLi. If you are the system administrator, please make sure PHP-MySQLi is installed.");
}
mysqli_options($cpconn, MYSQLI_OPT_CONNECT_TIMEOUT, 3); // TIMEOUT OF THE MYSQL CONNECTION, IN SECONDS
mysqli_real_connect($cpconn,$_CONFIG["db_host"], $_CONFIG["db_username"], $_CONFIG["db_password"], $_CONFIG["db_name"]);
if ($cpconn->connect_error) {
    dieWithError("We were unable to connect to the dashboard database. Here's the MySQLi error: <br/>" . mysqli_connect_error());
}

//
// Some functions
//
function getclientip() {
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP)) { $ip = $client; }
    elseif(filter_var($forward, FILTER_VALIDATE_IP)) { $ip = $forward; }
    else { $ip = $remote; }

    return $ip;
}


function dieWithError($message) {
    ?>
    <style>
        .centered {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .centered h1 {
            font-family: Arial, Helvetica, sans-serif;
            color: red;
            text-align: center;
        }
        .centered p {
            font-family: 'Courier New', Courier, monospace;
            text-align: center;
            color: red;
        }
    </style>
    <div class="centered">
        <h1>Uh oh... The dashboard cannot function normally!</h1>
        <p><?= $message ?></p>
    </div>
    <?php
    die();
}