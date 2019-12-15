<?php
error_reporting(0);
// Create Connection
require_once 'config.inc.php';
date_default_timezone_set('Asia/Kolkata');
// Create Connection

function base_url()
{
    $config['base_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
    $config['base_url'] .= "://".$_SERVER['HTTP_HOST'];
    $config['base_url'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
    return $config['base_url'];
}

function sendSMS($num,$msg)
{
    $smsUsername = "mlmindian";
    $smsPassword = "Abir@Tech95";
    $smsMobileNumber = $num;
    $smsBody = $msg;
    $smsCode = "FOODEG";

    $baseurl = "http://api.msg91.com/api/sendhttp.php?user=".$smsUsername."&password=".$smsPassword."&mobiles=".$smsMobileNumber."&message=".$smsBody."&sender=".$smsCode;
    return $baseurl;
}


function send_sms_function($number,$message)
{
    global $conn;
    if($stmt = $conn->
    prepare("SELECT id,type,value FROM settings WHERE type = 'sms'"))
    {
    $stmt->execute();
    $stmt->bind_result($id,$type,$value);
    $stmt->fetch();
    unset($stmt);
    if($value != "0")
    {
        $url = 'http://api.msg91.com/api/sendhttp.php';
        $fields = array(
            'user'=> "mlmindian",
            'password'=> "Abir@Tech95",
            'sender'=> "FOODEG",
            'mobiles'=> $number,
            'message'=> $message
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        
        mysqli_query($conn,"UPDATE settings SET value = value-1 WHERE type = 'sms'");

    }
    else
    {
        $output = "0 Message Balance";
    }
    }
    
    
    
    return $output;
}

function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
$ipaddress = get_client_ip();

$active_user = $_SESSION["superUserClassi"];
//$active_user = $_COOKIE['superUserFoodi'];

if($active_user != "")
{
  if($stmt = $conn->prepare("SELECT user.id, user.username, user.type, user.name, user.gender, user.phone, user.email, user.location,
  user.city AS cityid,
  user.state AS stateid,
  user.country AS countryid,
  user.thum,
  cities.name AS city,
  states.name AS states,
  countries.name AS country
  FROM user
  LEFT JOIN cities ON cities.id = user.city
  LEFT JOIN states ON states.id = user.state
  LEFT JOIN countries ON countries.id = user.country
 WHERE user.id=?"))
  {
    $stmt->bind_param("s",$active_user);
    $stmt->execute();
    $stmt->bind_result($getRowid,$usercode,$usertype,$username,$usergender,$usercontact,$useremail,$userlocation,$usercity_code,$userstate_code,$usercountry_code,$userdp,$usercity,$userstate,$usercountry);
    $stmt->store_result();
    $stmt->fetch();
  }
  $user = array(
      "id"=>"$getRowid",
      "username"=>"$usercode",
      "type"=>"$usertype",
      "name"=>"$username",
      "gender"=>"$usergender",
      "number"=>"$usercontact",
      "email"=>"$useremail",
      "address"=>"$userlocation",
      "city"=>"$usercity",
      "state"=>"$userstate",
      "country"=>"$usercountry",
      "country_code"=>"$usercountry_code",
      "state_code"=>"$userstate_code",
      "city_code"=>"$usercity_code",
      "user_dp"=>"$userdp"
  );
}
unset($stmt);


function logout($var,$url)
{
    if($_SESSION[$var] != "")
    {
        unset($_SESSION[$var]); 
        if($_SESSION[$var] == "")
        {
            header("location: $url");
            ?>
            <script>
                window.open("<?php echo $url; ?>","_self");
            </script>
            <?php
        }    	
    }
    else
    {
        header("location: $url");
        ?>
        <script>
            window.open("<?php echo $url; ?>","_self");
        </script>
        <?php
    }
}


function remove_space($text)
{
	$text = strip_tags($text);
    $freshText = str_replace('\r', ' ', $text);
    $freshText = str_replace('\n', ' ', $freshText);
    $freshText = str_replace('\\', ' ', $freshText);
    $freshText = str_replace(" \ ", ' ', $freshText);    
    $freshText = str_replace('\r\n', ' ', $freshText);    
    $freshText = str_replace('r\n', ' ', $freshText);
    $freshText = str_replace('n\r', ' ', $freshText);    
    $freshText = str_replace('r/n', ' ', $freshText);
    $freshText = str_replace('n/r', ' ', $freshText);    
    $freshText = str_replace('r/n', ' ', $freshText);
    $freshText = str_replace('n/r', ' ', $freshText); 
    $freshText = str_replace(' ', '-', $freshText);
    $freshText = strtolower($freshText);
    return preg_replace('/[^A-Za-z0-9\-]/', '', $freshText); // Removes special chars.
	
}

function remove_etc($text)
{
	$text = strip_tags($text);
	//$btc = " \ ";
	//$btc = str_replace(' ', '', $btc);    
    $freshText = str_replace('\r', ' ', $text);
    $freshText = str_replace('\n', ' ', $freshText);
    $freshText = str_replace('\\', ' ', $freshText);        
    $freshText = str_replace('\r\n', ' ', $freshText);    
    $freshText = str_replace('r\n', ' ', $freshText);
    $freshText = str_replace('n\r', ' ', $freshText);    
    $freshText = str_replace('r/n', ' ', $freshText);
    $freshText = str_replace('n/r', ' ', $freshText);    
    $freshText = str_replace('r/n', ' ', $freshText);
    $freshText = str_replace('n/r', ' ', $freshText); 
    $freshText = str_replace($btc, ' ', $freshText);
    return ($freshText);
}

function schort_len($var, $num)
{
    $var = strip_tags($var);
    if (strlen($var) > $num) 
    {
        echo substr($var, 0, $num) . "...";
    } else {
        echo substr($var, 0, $num);
    }
}

function get_header()
{
    global $sessionvar;
    include_once("header.php");
}

function get_footer()
{
    include_once("footer.php");
}

function globsecurity( $string, $action = 'e' )
{
    // you may change these values to your own
    $secret_key = '1311874k1965874';
    $secret_iv = 'globinationIndia';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }

    return $output;
}

function get_admin_details($user)
{
    global $conn;
    $query = mysqli_query($conn,"SELECT * FROM admin");
    $data = mysqli_fetch_array($query);
    return $data;
}

function getpagename()
{
    $get = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

    $myString = $get;
    $myArray = explode('.', $myString);
    $get = $myArray[0];
    
    $freshText = str_replace('-', ' ', $get);
    $freshText = str_replace('_', ' ', $freshText);
    return $freshText;
}

function deltext($string,$f,$l)
{
    
    $string = substr($string, $f, $l);
    echo $string;
}

function get_coutry_list()
{
    global $conn;
    $query = mysqli_query($conn, "SELECT * FROM country");
    while($rows = mysqli_fetch_array($query))
    {
        ?>
        <option value="<?php echo $rows[0]; ?>"><?php echo $rows[1]; ?></option>
        <?php
        
    }
}

function get_coutry_by_code($code)
{
    global $conn;
    $query = mysqli_query($conn, "SELECT * FROM country WHERE countrycode = '$code'");
    while($rows = mysqli_fetch_array($query))
    {
        echo $rows[1];        
    }
}

function get_onlu_date($date)
{
    $dt = new DateTime($date);
    return $dt->format('d/m/Y');
}
?>