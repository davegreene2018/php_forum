<?php
// Ajax calls this NAME CHECK code to execute
if(isset($_POST["usernamecheck"])){
  include_once("php_includes/db_conx.php");
  $username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
  $sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
    $uname_check = mysqli_num_rows($query);
    if (strlen($username) < 3 || strlen($username) > 16) {
      echo '<strong style="color:#F00;">3 - 16 characters please</strong>';
      exit();
    }
  if (is_numeric($username[0])) {
      echo '<strong style="color:#F00;">Usernames must begin with a letter</strong>';
      exit();
    }
    if ($uname_check < 1) {
      echo '<strong style="color:#009900;">' . $username . ' is OK</strong>';
      exit();
    } else {
      echo '<strong style="color:#F00;">' . $username . ' is taken</strong>';
      exit();
    }
}
?>
<?php
// Ajax calls this EMAIL CHECK code to execute
if(isset($_POST["emailcheck"])){
  include_once("php_includes/db_conx.php");
  $email = preg_replace('#[^!<>.@&\/\sA-Za-z0-9_]#i', '', $_POST['emailcheck']);
  $sql = "SELECT id FROM users WHERE email='$email' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
    $email_check = mysqli_num_rows($query);
    
    if ($email_check < 1) {
      echo '<strong style="color:#009900;">' . $email . ' is OK</strong>';
      exit();
    } else {
      echo '<strong style="color:#F00;">' . $email . ' is already registered</strong>';
      exit();
    }
}
?>
<?php
session_start();
// 
$error = '';
if(isset($_POST["register"])){
 
  // GATHER THE POSTED DATA INTO LOCAL VARIABLES
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $password_confirm = $_POST['password_confirm'];
  
    if($password != $password_confirm){
      $error = "Passwords do not match";
    } else {
    $p_hash = sha1($password);
    // GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
     // CONNECT TO THE DATABASE
    include_once("php_includes/db_conx.php");
    // Add user info into the database table for the main site table
    $sql = "INSERT INTO users(first_name, last_name, username, email, password) VALUES ('$first_name', '$last_name', '$username', '$email', '$p_hash')";

    $query = mysqli_query($db_conx, $sql);
    $insert_id = mysqli_insert_id($db_conx);
  
    // Create directory(folder) to hold each user's files(pics, MP3s, etc.)
    if (!file_exists("user/$username")) {
      mkdir("user/$username", 0755);
    }

    if($insert_id) {
      $_SESSION['user_id'] = $insert_id;
      $_SESSION['username'] = $username;
        header("location: ./home.php");
        
    } else {
      $error = "There was a problem processing your request '$first_name', '$last_name', '$username', '$email', '$p_hash' '$insert_id'";

    }
  
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
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function restrict(elem){
  var tf = _(elem);
  var rx = new RegExp;
  if(elem == "email"){
    rx = /[' "]/gi;
  } else if(elem == "username"){
    rx = /[^a-z0-9]/gi;
  }
  tf.value = tf.value.replace(rx, "");
}
function emptyElement(x){
  _(x).innerHTML = "";
}
// check if the username is already taken
function checkusername(){
  var u = _("username").value;
  if(u != ""){
    _("unamestatus").innerHTML = 'checking ...';
    var ajax = ajaxObj("POST", "register.php");
        ajax.onreadystatechange = function() {
          if(ajaxReturn(ajax) == true) {
              _("unamestatus").innerHTML = ajax.responseText;
          }
        }
        ajax.send("usernamecheck="+u);
  }
}
// check is the email is already taken
function checkemail(){
  var e = _("email").value;
  if(e != ""){
    _("emailstatus").innerHTML = 'checking ...';
    var ajax = ajaxObj("POST", "register.php");
        ajax.onreadystatechange = function() {
          if(ajaxReturn(ajax) == true) {
              _("emailstatus").innerHTML = ajax.responseText;
          }
        }
        ajax.send("emailcheck="+e);
  }
}

</script>
</head>

<body>
<?php include_once("page_top.php"); ?>

	

  <section class="hero is-primary is-fullheight">
  <div class="hero-body">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-5-tablet is-4-desktop is-3-widescreen">
        	<h1 align="center">Register a new account</h1>
          <form action="register.php"  method="post" class="box">
          	 <div class="field">
              <label for="" class="label">First name</label>
              <div class="control has-icons-left">
                <input id="first_name" name="first_name" type="text" placeholder="First name" class="input" onfocus="emptyElement('status')"  maxlength="88">
                <span class="icon is-small is-left">
                  <i class="fa fa-envelope"></i>
                </span>
              </div>
            </div>
            <div class="field">
              <label for="" class="label">Last name</label>
              <div class="control has-icons-left">
                <input id="last_name" name ="last_name" type="text" placeholder="Last name" class="input" onfocus="emptyElement('status')"  maxlength="88">
                <span class="icon is-small is-left">
                  <i class="fa fa-envelope"></i>
                </span>
              </div>
            </div>
             <div class="field">
              <label for="" class="label">Username</label>
              <div class="control has-icons-left">
                <input id="username" name="username" type="text" placeholder="Username" class="input"  onblur="checkusername()" onkeyup="restrict('username')" maxlength="16">
                <span class="icon is-small is-left">
                  <i class="fa fa-envelope"></i>
                </span>
                <span id="unamestatus"></span>
              </div>
            </div>
             <div class="field">
              <label for="" class="label">Email</label>
              <div class="control has-icons-left">
                <input id="email" name="email" type="email" placeholder="e.g. bobsmith@gmail.com" class="input"  onblur="checkemail()" onkeyup="restrict('email')" maxlength="88">
                <span class="icon is-small is-left">
                  <i class="fa fa-envelope"></i>
                </span>
                <span id="emailstatus"></span>
              </div>
            </div>
            <div class="field">
              <label for="" class="label">Password</label>
              <div class="control has-icons-left">
                <input id="password" name="password" type="password" placeholder="*******" class="input" onfocus="emptyElement('status')" maxlength="16">
                <span class="icon is-small is-left">
                  <i class="fa fa-lock"></i>
                </span>
              </div>
            </div>
             <div class="field">
              <label for="" class="label">Confirm password</label>
              <div class="control has-icons-left">
                <input id="password_confirm" name="password_confirm" type="password" placeholder="*******" class="input" onfocus="emptyElement('status')" maxlength="16">
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
                <input type ="submit" id="register" name="register" class="button is-info" value="Create account">
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