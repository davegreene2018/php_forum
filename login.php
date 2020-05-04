<?php
session_start();
$error = '';


if(@$_POST['submit']){
	$email = $_POST['email'];
  $password = sha1($_POST['password']);


	include_once("php_includes/db_conx.php");

	$sql = "SELECT * FROM users WHERE email='$email' AND password= '$password'";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query)>0){
    	while($row = mysqli_fetch_array($query)){
        $userId = $row[0];
        $username = $row[3];
        $db_pass = $row[5];
        if($password != $db_pass){
          echo "Login failed";
        } else {
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
    		header("location: ./home.php?u=$username");
      }

    		
    	}
    }
    else {
    	$error = "Username and password do not match";
    }

	
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Alumni Ireland</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.5.2/css/bulma.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	

</head>

<body>
<?php include_once("page_top.php"); ?>

	

  <section class="hero is-primary is-fullheight">
  <div class="hero-body">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-5-tablet is-4-desktop is-3-widescreen">
          <form action="login.php" method="post" class="box">
            <div class="field">
              <label for="" class="label">Email</label>
              <div class="control has-icons-left">
                <input name="email" type="email" placeholder="e.g. bobsmith@gmail.com" class="input" required>
                <span class="icon is-small is-left">
                  <i class="fa fa-envelope"></i>
                </span>
              </div>
            </div>
            <div class="field">
              <label for="" class="label">Password</label>
              <div class="control has-icons-left">
                <input name="password" type="password" placeholder="*******" class="input" required>
                <span class="icon is-small is-left">
                  <i class="fa fa-lock"></i>
                </span>
              </div>
            </div>
            <div class="field">
              <label for="" class="checkbox">
                <input type="checkbox">
               Remember me
              </label>
            </div>
            <div class="field">
              <input type ="submit" name="submit" class="button is-info" value="login">
                
              
            </div>
            <p class="help is-danger"><?php echo $error; ?></p>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
	





</body>
</html>