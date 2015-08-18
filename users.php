<?php include_once("header.php"); ?>

<?php
require('api/src/OAuth2/Client.php');
require('api/src/OAuth2/GrantType/IGrantType.php');
require('api/src/OAuth2/GrantType/AuthorizationCode.php');
require('api/src/File_k/index.php');
// Create Authentication
$clientId = CLI_ID;
$clientSecret = CLI_SEC;
$client = new OAuth2\Client($clientId, $clientSecret);

$redirectUrl    = 'http://www.myapp.com/oauth_callback';
$authorizeUrl   = 'https://corewaysolution.nationbuilder.com/oauth/authorize';
$authUrl = $client->getAuthenticationUrl($authorizeUrl, $redirectUrl);
// set the client token
$token = 'MyaccessToken';
$client->setAccessToken($token);


$baseApiUrl = 'https://corewaysolution.nationbuilder.com';
$client->setAccessToken($token);





if(isset($_GET['editdata']) && $_GET['editdata'] == 'yes') {
	
	if(isset($_POST['submitupdate'])){
		$fname = $_POST['fname'];
		$mobile = $_POST['mobile'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$country = $_POST['country'];
		$zip = $_POST['zip'];
		
		$params = array('person' => array('phone'=>$mobile,'first_name'=>$fname,'last_name'=>$lname,'email'=>$email,'home_address' => array('country_code' => $country, 'zip' => $zip )));
		$header = array('Authorization:' => $token,'Content-Type:' => 'application/json', 'Accept:' => 'application/json');
		$response = $client->fetch($baseApiUrl . '/api/v1/people/'.$_GET['editperson'], $params, 'PUT',$header);
		$url = "http://" . $_SERVER['SERVER_NAME']."/nb-test/users.php";
		echo "<script>location.href='$url'</script>";
	}
	
	else {
	$response = $client->fetch($baseApiUrl . '/api/v1/people/'.$_GET['editperson']);
	$finalarray = $response['result']['person'];
?>
<div class="navigator edit_nb">
	<form method="post" action="<?php echo "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>" >
		<h2 align="center">Update <?php echo $finalarray['first_name'];?></h2>
        <table  border="0" cellpadding="5" cellspacing="0" align="center" bordercolor="#CCCCCC"  style="width:100%;">
        	<tr>
            	<th>Firstname</th>
                <td><input type="text" name="fname" value="<?php echo $finalarray['first_name'];?>"/></td>
            </tr><tr>
            	<th>Lastname</th>
                <td><input type="text" name="lname" value="<?php echo $finalarray['last_name'];?>"/></td>
            </tr>
            <tr>
            	<th>Email ID</th>
                <td><input type="text" name="email"  value="<?php echo $finalarray['email'];?>"/></td>
            </tr>
        	<tr>
            	<th>Mobile</th>
                <td><input type="text" name="mobile" value="<?php echo $finalarray['phone'];?>"/></td>
            </tr>
            <tr>
            	<th>Country</th>
                <td><input type="text" name="country" value="<?php echo $finalarray['home_address']['country_code'];?>" /></td>
            </tr>
            <tr>
            	<th>Zip</th>
                <td><input type="text" name="zip" value="<?php echo $finalarray['home_address']['zip'];?>"/></td>
            </tr>
        	
            
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
	$params = array('person' => array('id'=>$deleteid));
	$header = array('Authorization:' => $token,'Content-Type:' => 'application/json', 'Accept:' => 'application/json');
	$response = $client->fetch($baseApiUrl . '/api/v1/people/'.$_GET['deleteperson'], $params, 'DELETE',$header);
	$url = "http://" . $_SERVER['SERVER_NAME']."/nb-test/users.php";
	echo "<script>location.href='$url'</script>";
}
else if(isset($_GET['addnew']) && $_GET['addnew'] == 'yes') { 
	
	if(isset($_POST['submitadd'])){
		$fname = $_POST['fname'];
		$mobile_add = $_POST['mobile_add'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$country = $_POST['country'];
		$zip = $_POST['zip'];
		/*echo "<pre>";
		print_r($_POST);
		echo "</pre>";*/
		$params = array('person' => array('phone'=>$mobile_add,'first_name'=>$fname,'last_name'=>$lname,'email'=>$email,'home_address' => array('country_code' => $country, 'zip' => $zip )));
	/*	echo "<pre>";
		print_r($params);
		echo "</pre>";
		*/

		//for Insert into person
		$header = array('Authorization:' => $token,'Content-Type:' => 'application/json', 'Accept:' => 'application/json');
		$response = $client->fetch($baseApiUrl . '/api/v1/people/push?access_token='.$token, $params, 'PUT',$header);
		$url = "http://" . $_SERVER['SERVER_NAME']."/nb-test/users.php";
		echo "<script>location.href='$url'</script>";
	}

else {
?>
<div class="navigator add_new">
<form method="post" action="<?php echo "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>" id="user_addnew" >
		<h2 align="center">Add New</h2>
        <table  border="0" cellpadding="6" cellspacing="0" align="center" bordercolor="#CCCCCC"  style="width:100%;">
        	<tr>
            	<th> Firstname </th>
                <td><input type="text" name="fname"  /></td>
            </tr>
        	<tr>
            	<th>Lastname</th>
                <td><input type="text" name="lname" /></td>
            </tr>
            <tr>
            	<th>Email ID</th>
                <td><input type="text" name="email" /></td>
            </tr>
        	<tr>
            	<th>Mobile</th>
                <td><input type="text" name="mobile_add" /></td>
            </tr>
            <tr>
            	<th>Country</th>
                <td><input type="text" name="country" /></td>
            </tr>
            <tr>
            	<th>Zip</th>
                <td><input type="text" name="zip" /></td>
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
else {
// For update into person
$response = $client->fetch($baseApiUrl . '/api/v1/people');

if(count($response['result']['results']) > 0) { 
$finalarray = $response['result']['results'];

?>

<h2 align="center">Manage users</h2>
<div class="navigator">
<a href="index.php"> &lt;&lt;  Back to Home </a> <a href="?addnew=yes" style="float:right;">Add New People</a>
</div>

<table  border="1" cellpadding="5" cellspacing="0" align="center" bordercolor="#CCCCCC"  style="width:100%;">
	<tr>
    	<th>Firstname</th>
        <th>Lastname</th>
        <th>Email</th>
        <th>Mobile</th>
         <th>Country Code</th>
        <th colspan="2">Action</th>
    </tr>
	<?php for($i=0;$i<count($finalarray);$i++) { ?>
	<tr>
		<td><?php echo $finalarray[$i]['first_name'];?></td>
        <td><?php echo $finalarray[$i]['last_name'];?></td>
    	<td><?php echo $finalarray[$i]['email'];?></td>
        <td><?php echo $finalarray[$i]['phone'];?></td>
         <td><?php echo $finalarray[$i]['primary_address']['country_code'];?></td>
        <td><a href="?editdata=yes&editperson=<?php echo $finalarray[$i]['id'];?>">Edit</a></td>
        <td><a href="?deletedata=yes&deleteperson=<?php echo $finalarray[$i]['id'];?>">Delete</a></td>
	</tr>
    <?php } ?>
</table>
<?php }  } ?>
<?php include_once("footer.php"); ?>
<style>
.error{color:red;}
.navigator.edit_nb input[type='text']{width:50%; height:30px;}
.navigator.edit_nb input[type='submit'] {width:100px; height:30px;}
.navigator.edit_nb table th{text-align:left; float:left;}
.navigator.add_new table th {text-align:left; float:left;}
.navigator.add_new input[type='text'] {width:50%; height:30px;}
.navigator.add_new input[type='submit'] {width:100px; height:30px; float:left;}
.input[type=text]:focus {border:none;}
</style>