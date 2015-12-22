<?php error_reporting(0); include_once("header.php"); ?>
<?php
require('api/src/OAuth2/Client.php');
require('api/src/OAuth2/GrantType/IGrantType.php');
require('api/src/OAuth2/GrantType/AuthorizationCode.php');
require('api/src/File_k/keys.php');
// Create Authentication
$clientId = CLI_ID;
$clientSecret = CLI_SEC;
$client = new OAuth2\Client($clientId, $clientSecret);

$redirectUrl    = 'http://www.myapp.com/oauth_callback';
$authorizeUrl   = 'https://corewaysolution.nationbuilder.com/oauth/authorize';
$authUrl = $client->getAuthenticationUrl($authorizeUrl, $redirectUrl);
// set the client token
$token = 'a70ff8aa9977925c4348d12f0b55b36011714972d086d0ac40d3fceb3eae7434';
$client->setAccessToken($token);


$baseApiUrl = 'https://corewaysolution.nationbuilder.com';
$client->setAccessToken($token);
?>
<script>
function calljavascript(str){ 
	var lowerstr = str.toLowerCase();
	var myarr = lowerstr.split(" ");
	var myarr = myarr.join("_");
	document.getElementById("nametochange").value=myarr;
}
</script>
<?php




if(isset($_GET['editdata']) && $_GET['editdata'] == 'yes') {
	
	if(isset($_POST['submitupdate'])){
		$name = $_POST['name'];
		$slug = $_POST['slugname'];
		$status_id = $_POST['status_id'];
		
		$vname = $_POST['vname'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$country_code = $_POST['country_code'];
		$zip = $_POST['zip'];
		$end_time = $_POST['end_time'];
		$start_time = $_POST['start_time'];
		
		$params = array('event' => array('venue'=>array('name'=>$vname,'address'=>array('address1'=>$address1,'address2'=>$address2,'city'=>$city,'state'=>$state,'country_code'=>$country_code,'zip'=>$zip)),'author_id'=>2,'calendar_id'=>1,'show_guests'=>1,'autoresponse'=>array('broadcaster_id'=>1,'subject'=>'RSVP confirmation for '. ucfirst($name)),'name'=>$name,'slug'=>$slug,'status'=>$status_id,'end_time'=>$end_time,'start_time'=>$start_time));
		$header = array('Authorization:' => $token,'Content-Type:' => 'application/json', 'Accept:' => 'application/json');
		$response = $client->fetch($baseApiUrl . '/api/v1/sites/corewaysolution/pages/events/'.$_GET['editperson'], $params, 'PUT',$header);
		$url = "http://" . $_SERVER['SERVER_NAME']."/nb-test/events.php";
		echo "<script>location.href='$url'</script>";
	}
	
	else {
	$response = $client->fetch($baseApiUrl . '/api/v1/sites/corewaysolution/pages/events/'.$_GET['editperson']);

	$finalarray = $response['result']['event'];
?>
<div class="navigator edit_event">
<form method="post" action="<?php echo "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>" >
		<h2 align="center">Update <?php echo $finalarray['name'];?> Event</h2>
        <table  border="0" cellpadding="5" cellspacing="0" align="center" bordercolor="#CCCCCC" width="100%;">
        	<tr>
            	<th>Event Name</th>
                <td><input type="text" name="name" value="<?php echo $finalarray['name'];?>" onchange="return calljavascript(this.value)" /></td>
            </tr>
        	<tr>
            	<th>Slug</th>
                <td><input type="text" name="slugname"  value="<?php echo $finalarray['slug'];?>" id="nametochange"/></td>
            </tr>
            <tr>
            	<th>Venue name</th>
                <td><input type="text" name="vname"  value="<?php echo $finalarray['venue']['name'];?>" /></td>
            </tr>
            <tr>
            	<th>Street address</th>
                <td><input type="text" name="address1" value="<?php echo $finalarray['venue']['address']['address1'];?>"  /></td>
            </tr>
            <tr>
            	<th>Apt, Suite, etc.</th>
                <td><input type="text" name="address2" value="<?php echo $finalarray['venue']['address']['address2'];?>" /></td>
            </tr>
            <tr>
            	<th>City</th>
                <td><input type="text" name="city" value="<?php echo $finalarray['venue']['address']['city'];?>" /></td>
            </tr>
            <tr>
            	<th>State</th>
                <td><input type="text" name="state" value="<?php echo $finalarray['venue']['address']['state'];?>" /></td>
            </tr>
            <tr>
            	<th>Country</th>
                <td>
                	<select name="country_code" >
                        <option value="US" <?php if($finalarray['venue']['address']['country_code']=='US') { ?> selected="selected" <?php } ?>  >USA</option>
                        <option value="IN"  <?php if($finalarray['venue']['address']['country_code']=='IN') { ?> selected="selected" <?php } ?>>India</option>
					</select>
                </td>
            </tr>
            <tr>
            	<th>Postal code</th>
                <td><input type="text" name="zip" value="<?php echo $finalarray['venue']['address']['zip'];?>" /></td>
            </tr>
              <tr>
            	<th>Start Time</th>
                <td> <input type="text" name="start_time"  value="<?php echo $finalarray['start_time'];?>" /></td>
            </tr>
              <tr>
            	<th>End Time</th>
                <td><input type="text" name="end_time"  value="<?php echo $finalarray['end_time'];?>" /></td>
            </tr>
            <input type="hidden" name="status_id"  value="<?php echo $finalarray['status'];?>" />
              
               
<!--            <tr>
            	<td>Status</td>
                <td>
                	<select name="status_id" >
                        <option value="unlisted" selected="selected">unlisted</option>
                        <option value="published">published</option>
					</select>
                </td>
            </tr>-->
            <tr>
            	<td colspan="2" align="center"><input type="submit" name="submitupdate" value="Update"/></td>
            </tr>
		</table>
	</form>
    </div>
<?php	
	}
}
else if(isset($_GET['deletedata']) && $_GET['deletedata'] == 'yes') {
	$deleteid = $_GET['deleteperson'];
	$params = array('event' => array('id'=>$deleteid));
	$header = array('Authorization:' => $token,'Content-Type:' => 'application/json', 'Accept:' => 'application/json');
	$response = $client->fetch($baseApiUrl . '/api/v1/sites/corewaysolution/pages/events/'.$_GET['deleteperson'], $params, 'DELETE',$header);
	$url = "http://" . $_SERVER['SERVER_NAME']."/nb-test/events.php";
		echo "<script>location.href='$url'</script>";
}
else if(isset($_GET['addnew']) && $_GET['addnew'] == 'yes') { 
	
	if(isset($_POST['submitadd'])){
		$name = $_POST['name'];
		$slug = $_POST['slugname'];
		$status_id = $_POST['status_id'];
		
		$vname = $_POST['vname'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$country_code = $_POST['country_code'];
		$zip = $_POST['zip'];
		$dateplus = date("d")+1;
		$hourplus = date("H")+3;
		$end_time = date("Y-m")."-".$dateplus."T".$hourplus.":".date("i:sP");
		$start_time = date("Y-m")."-".$dateplus."T".date("H:i:sP");
		
		$params = array('event' => array('venue'=>array('name'=>$vname,'address'=>array('address1'=>$address1,'address2'=>$address2,'city'=>$city,'state'=>$state,'country_code'=>$country_code,'zip'=>$zip)),'author_id'=>2,'calendar_id'=>1,'show_guests'=>1,'autoresponse'=>array('broadcaster_id'=>1,'subject'=>'RSVP confirmation for '. ucfirst($name)),'name'=>$name,'slug'=>$slug,'status'=>$status_id,'end_time'=>$end_time,'start_time'=>$start_time));
		//for Insert into event
		$header = array('Authorization:' => $token,'Content-Type:' => 'application/json', 'Accept:' => 'application/json');
		$response = $client->fetch($baseApiUrl . '/api/v1/sites/corewaysolution/pages/events', $params, 'POST',$header);
		
		
		$url = "http://" . $_SERVER['SERVER_NAME']."/nb-test/events.php";
		echo "<script>location.href='$url'</script>";
	}

else {
?>
<div class="navigator add_new_event">
<form method="post" action="<?php echo "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>" id="user_addnew_event" >
		<h2 align="center">Add New Event</h2>
        <table  border="0" cellpadding="5" cellspacing="1" align="center" width="100%">
        	<tr>
            	<th>Event Name</th>
                <td><input type="text" name="name" onchange="return calljavascript(this.value)" /></td>
            </tr>
        	<tr>
            	<th>Slug</th>
                <td><input type="text" name="slugname" id="nametochange"/></td>
            </tr>
            <tr>
            	<th>Venue name</th>
                <td><input type="text" name="vname" id="nametochange"/></td>
            </tr>
            <tr>
            	<th>Street address</th>
                <td><input type="text" name="address1" /></td>
            </tr>
            <tr>
            	<th>Apt, Suite, etc.</th>
                <td><input type="text" name="address2" /></td>
            </tr>
            <tr>
            	<th>City</th>
                <td><input type="text" name="city" /></td>
            </tr>
            <tr>
            	<th>State</th>
                <td><input type="text" name="state"/></td>
            </tr>
            <tr>
            	<th>Country</th>
                <td>
                	<select name="country_code" >
                        <option value="US" >USA</option>
                        <option value="IN">India</option>
					</select>
                </td>
            </tr>
            <tr>
            	<th>Postal code</th>
                <td><input type="text" name="zip"/></td>
            </tr>
            <tr>
            	<th>Status</th>
                <td>
                	<select name="status_id" >
                        <option value="unlisted" selected="selected">unlisted</option>
                        <option value="published">published</option>
					</select>
                </td>
            </tr>
            <tr>
            	<td colspan="2" align="center"><input type="submit" name="submitadd" value="Save"/></td>
            </tr>
		</table>
	</form>
    </div>
    
<?php    
	}
}

else if(isset($_GET['event_rsvp']) && $_GET['event_rsvp'] != '') {
	if(isset($_POST['submitrsvp'])){
		
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
     	$email = $_POST['email'];
		
		$mobile_number = $_POST['mobile_number'];
		$guests_count = $_POST['guests_count'];

		$paframs = array('person' => array('phone'=>$mobile_number,'first_name'=>$first_name,'last_name'=>$last_name,'email'=>$email));
		$header = array('Authorization:' => $token,'Content-Type:' => 'application/json', 'Accept:' => 'application/json');
		$response = $client->fetch($baseApiUrl . '/api/v1/people/push?access_token='.$token, $paframs, 'PUT',$header);
		

		$person_id = $response['result']['person']['id'];
		$pams_for = array('rsvp' => array('person_id'=>$person_id,'guests_count'=>$guests_count,'canceled'=>'false'));
		
		$header = array('Authorization:' => $token,'Content-Type:' => 'application/json', 'Accept:' => 'application/json');
		//$res_fponse = $client->fetch($baseApiUrl . '/api/v1/sites/corewaysolution/pages/events/'.$_GET['event_rsvp'].'/rsvps', $params, 'POST',$header);
		$res_fponse = $client->fetch($baseApiUrl . '/api/v1/sites/corewaysolution/pages/events/'.$_GET['event_rsvp'].'/rsvps/', $pams_for, 'POST',$header);
	
		
		$url = "http://" . $_SERVER['SERVER_NAME']."/nationbuilder/events.php";
		echo "<script>location.href='$url'</script>";
	}
	
else{
	
	// $response = $client->fetch($baseApiUrl . '/api/v1/sites/corewaysolution/pages/events//rsvps');
    // $response = $client->fetch($baseApiUrl . '/api/v1/sites/corewaysolution/pages/247/event/rsvps');
	// print_r( $response);
	//  $response = $client->fetch($baseApiUrl . '/api/v1/sites/corewaysolution/pages/events/35');
	//  $finalarray = $response['result']['event']; 
  //  print_r( $finalarray);
?>
<div class="navigator add_new_event">

<form method="post" action="<?php echo "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>" id="user_rsvp_event" >
		<h2 align="center">Add RSVp</h2>
        <table  border="0" cellpadding="5" cellspacing="1" align="center" width="100%">
        	<tr>
            	<th>first name</th>
                <td><input type="text" name="first_name" onchange="return calljavascript(this.value)" /></td>
            </tr>
        	<tr>
            	<th>last name</th>
                <td><input type="text" name="last_name" id="nametochange"/></td>
            </tr>
            <tr>
            	<th>email</th>
                <td><input type="text" name="email" id="nametochange"/></td>
            </tr>
            <tr>
            	<th>mobile_number</th>
                <td><input type="text" name="mobile_number" /></td>
            </tr>
            <tr>
            	<th>guests count</th>
                <td><input type="text" name="guests_count" /></td>
            </tr>
            
            <tr>
            	<td colspan="2" align="center"><input type="submit" name="submitrsvp" value="Save"/></td>
            </tr>
		</table>
	</form>
    </div>
    
<?php    
	
	}	
	
}

else {

// For update into person
$response = $client->fetch($baseApiUrl . '/api/v1/sites/corewaysolution/pages/events');
$next = explode("?",$response['result']['next']);
$next = $next[1];
$prev = $response['result']['prev'];
	if(isset($_GET['next'])){
		echo $_GET['next'];
		$response = $client->fetch($baseApiUrl . '/api/v1/sites/corewaysolution/pages/events?__nonce=X42fpehWn282nLnC95BgoA&__token=AMw9e-u9wBfOpmEfuK72RrLcTBFMJ3Pbr3hfZUwcg1ZS');
	}
if(count($response['result']['results']) > 0) { 
	$finalarray = $response['result']['results'];
?>
<h2 align="center">Events for <?php echo $finalarray[0]['site_slug'];?></h2>
<div class="navigator">
<a href="index.php"> &lt;&lt; Back to Home </a> <a href="?addnew=yes" style="float:right;" >Add New Event</a>
</div>
<table  border="1" cellpadding="5" cellspacing="0" align="center" bordercolor="#CCCCCC">
	<tr>
    	<th>Name</th>
        <th>Slug</th>
        <th>When</th>
        <th>Venue</th>        
        <th>Status</th>
        <th>RSVP</th>
        <th colspan="2">Action</th>
    </tr>
	<?php for($i=0;$i<count($finalarray);$i++) { ?>
	<tr>
		<td><?php echo $finalarray[$i]['name'];?></td>
        <td><?php echo $finalarray[$i]['slug'];?></td>
    	<td><?php $starttime = explode("T",$finalarray[$i]['start_time']);
					$explodedate = explode("-",$starttime[0]);
					$normaldate = $explodedate[2]."-".$explodedate[1]."-".$explodedate[0];
					echo date("F d",strtotime($normaldate));
				
		
		
		?></td>
        <td><?php echo $finalarray[$i]['venue']['name']; ?></td>
        <td><?php echo $finalarray[$i]['status']; ?></td>
         <td><a href="?event_rsvp=<?php echo $finalarray[$i]['id'];?>">Send RSVP</a></td>
        <?php if($finalarray[$i]['status'] != "expired") { ?>
        <td><a href="?editdata=yes&editperson=<?php echo $finalarray[$i]['id'];?>">Edit</a></td>
        <?php } else { ?>
        <td></td>
        <?php } ?>
        <td><a href="?deletedata=yes&deleteperson=<?php echo $finalarray[$i]['id'];?>">Delete</a></td>
	</tr>
    <?php } ?>
</table>
<?php }  
} ?>


<?php include_once("footer.php"); ?>
<style>
.error{color:red;}
.edit_event table th {text-align:left; float:left;}
.edit_event input[type='text']{width:50%; height:30px;}
.edit_event input[type='submit']{width:100px; height:30px;}
.add_new_event table th {text-align:left; float:left;}
.navigator.add_new_event input[type='text'] {width:50%; height:25px;}
.navigator.add_new_event select {width:50%; height:30px;}
.navigator.add_new_event input[type='submit'] {width:100px; height:30px; float:left;}
</style>