<?php
////////////////////////////////////////////////////
//// Simple TeamSpeak Server Creator v1.0      ////
//// Copyright: @Noawin                       ////
//// GITHUB: Noawin                          ////
////////////////////////////////////////////////
	date_default_timezone_set('Europe/Paris'); //Change Here to your locale timezone!
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	include 'data/config.php';
	if (isset($_POST["create"])) {
		$connect = "serverquery://".$USER_QUERY.":".$PASS_QUERY."@".$HOST_QUERY.":".$PORT_QUERY."";
    		$ts3 = TeamSpeak3::factory($connect);
		$servername = $_POST['servername'];
		$slots = 32;
		$port = rand(12000,13000);
		$unixTime = time();
		$realTime = date('[Y-m-d]-[H:i]',$unixTime);
        $create_array = [
            "virtualserver_name" => $servername,
            "virtualserver_maxclients" => $slots,
            "virtualserver_name_phonetic" => $realTime,
            "virtualserver_hostbutton_tooltip" => "HostyourServer",
            "virtualserver_hostbutton_url" => "https://HostyourServer.de/",
            "virtualserver_hostbutton_gfx_url" => "http://drhalgreen.com/wp-content/uploads/2013/09/radio_icon_660px.png",
        ];
		
		if(!empty($port)) {
            array_merge($create_array, ["virtualserver_port" => $port]);
        }
        $curl = curl_init("https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($curl, CURLOPT_POST, 2);
        curl_setopt($curl, CURLOPT_POSTFIELDS, ['secret' => $GOOGLE_CAPTCHA_PRIVATEKEY, 'response' => $_POST['g-recaptcha-response']]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec ($curl);
        curl_close ($curl);
        if(json_decode($response, TRUE)['success']) {
            try {
                $new_ts3 = $ts3->serverCreate($create_array);
                $token = $new_ts3['token'];
                $createdport = $new_ts3['virtualserver_port'];
		
            } catch (Exception $e) {
                echo "Error (ID " . $e->getCode() . ") <b>" . $e->getMessage() . "</b>";
            }
        }else {
            die;
        }
		
	}
	$version = file_get_contents("./version");
?>
<!DOCTYPE html>
<html lang="hu" class="no-js">
    <head>
	<meta content="freets, ts,free ts3, kostenlose teamspeak3 Server" name="keywords">
		<meta content="HostyourServer.de, Free ts Service." name="description">
        <meta charset="UTF-8" />
        <title>TS Creator :: Hostyourserver</title>
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
    <body>
        <div class="container">
            <header>
				<h1>Hostyourserver</h1>
				<h2><b>Unser Free Teamspeak Server Creator</b></h2>
			</header>
			           <section>				
                <div id="container_demo" >
                    <div id="wrapper">
                        <div id="login" class="animate form">
							<?php if (isset($_POST["create"])): ?>
								<form  method="post" autocomplete="off"> 
									
									<h1>Server Created!</h1> 
									
									<p> 
										<label  class="uname" data-icon="u" > Server Name</label>
										<input readonly type="text" value="<?php echo $servername; ?>"/>
									</p>
									
									<p> 
										<label  class="uname" data-icon="u" > Server Admin Token</label>
										<input readonly type="text" value="<?php echo $token; ?>"/>
									</p>
									
									<p> 
										<label  class="uname" data-icon="u" > Server Port</label>
										<input readonly type="text" value="<?php echo $createdport; ?>"/>
									</p>
																		<p class="login button"> 
                                        <a href="<?php echo "ts3server://$host?port=$createdport&token=$token"; ?>" target=""> <input type="button" value="Connect!"> </a>  
                                    </p>
								</form>
                            <?php else: ?>
								<form  method="post" autocomplete="off"> 
									<h1>Ts Server Creator</h1> 
									<p> 
										<label  class="uname" data-icon="u" > Server Name</label>
										<input  name="servername" required="required" type="text" placeholder="Server Name"/>
									</p>
									
									<!--<p> 
										<label class="youpasswd" data-icon="p"> Slots</label>
										<input name="slots" required="required" type="text"   placeholder="max 32 more you cant connect" /> 
									</p>-->
								
                                    <div class="g-recaptcha" data-sitekey="<?=$GOOGLE_CAPTCHA_PUBLICKEY?>"></div>
									
									<p class="login button"> 
										<input type="submit" name="create" value="Create!" /> 
									</p>
								</form>
							<?php endif; ?>
			<footer><center>
<div class="col-md-9">
<p><strong>Copyright &copy; 2019 - 2020<span style="text-decoration: underline;"><a href="http://hostyourserver.de/"> Hostyourserver.de</a></span></strong></p>
      </div>
			</center></footer>
		</div>
		</section>
<section>
	<div class="footer">
            <div style="align-self: center; position: fixed; left: 5px;">Open source on <a href="#" style="display: inline-block; position: relative">github.com</a></div>
            <div style="align-self: center; right: 5px">TeamSpeak Server Creator (<?php echo $version; ?>) by Noawin</div>
        </div>
			</section>
	</body>
</html>																							