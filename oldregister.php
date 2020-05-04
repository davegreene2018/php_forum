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
// Ajax calls this NAME CHECK code to execute
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
// Ajax calls this REGISTRATION code to execute
error_reporting(E_ALL);
if(isset($_POST["u"])){
  // CONNECT TO THE DATABASE
  include_once("php_includes/db_conx.php");
  // GATHER THE POSTED DATA INTO LOCAL VARIABLES
  $f = preg_replace('#[^a-z0-9]#i', '', $_POST['f']);
  $l = preg_replace('#[^a-z0-9]#i', '', $_POST['l']);
  $u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
  $e = mysqli_real_escape_string($db_conx, $_POST['e']);
  $p = $_POST['p'];
  // GET USER IP ADDRESS
  $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
  
  // DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
  $sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
  $u_check = mysqli_num_rows($query);
  // -------------------------------------------
  $sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
  $e_check = mysqli_num_rows($query);
  // FORM DATA ERROR HANDLING
  if($u == "" || $p == ""){
    echo "The form submission is missing values '.$u.''.$e.''.$p.'.";
        exit();
  } else if ($u_check > 0){ 
        echo "The username you entered is alreay taken";
        exit();
  } else if ($e_check > 0){ 
        echo "That email address is already in use in the system";
        exit();
  } else if (strlen($u) < 3 || strlen($u) > 16) {
        echo "Username must be between 3 and 16 characters";
        exit(); 
    } else if (is_numeric($u[0])) {
        echo 'Username cannot begin with a number';
        exit();
    } else {
    
    $p_hash = sha1($p);
    $email = $e;
    // Add user info into the database table for the main site table
    $sql = "INSERT INTO users (first_name, last_name, username, email, password, signup, lastlogin)       
            VALUES('$f', '$l', '$u', '$e', '$p_hash', now(), now())";
    
    $query = mysqli_query($db_conx, $sql);
    $id = mysqli_insert_id($db_conx);
     
    
  
    // Create directory(folder) to hold each user's files(pics, MP3s, etc.)
    if (!file_exists("user/$u")) {
      mkdir("user/$u", 0755);
    }

    // Email the user their activation link
   // $to = "$e";              
   // $from = "Admin@ulumniireland.ie";
    //$subject = ' Account Activation';
    //$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>picksandlicks.ie Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#000; font-size:24px; color:#CCC;"><a href="http://www.picksandlicks.ie"></a>picksandlicks.ie Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="picksandlicks.ie/activation.php?id='.$id.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">Click here to activate your account</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';
    //$headers = "From: $from\n";
        //$headers .= "MIME-Version: 1.0\n";
        //$headers .= "Content-type: text/html; charset=iso-8859-1\n";
    //mail($to, $subject, $message, $headers);
    echo "signup_success";
    exit();
  }
  exit();
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
function signup(){
  var f = _("fname").value;
  var l = _("lname").value;
  var u = _("username").value;
  var e = _("email").value;
  var p1 = _("pass1").value;
  var p2 = _("pass2").value;
  var status = _("status");
  if(f == "" || l == ""|| u == "" || e == "" || p1 == "" || p2 == ""){
    status.innerHTML = "Fill out all of the form data";
  } else if(p1 != p2){
    status.innerHTML = "Your password fields do not match";
  } else if( _("terms").style.display == "none"){
    status.innerHTML = "Please view the terms of use";
  } else {
    _("signupbtn").style.display = "none";
    status.innerHTML = 'please wait ...';
    var ajax = ajaxObj("POST", "register.php");
        ajax.onreadystatechange = function() {
          if(ajaxReturn(ajax) == true) {
              if(ajax.responseText != "signup_success"){
          status.innerHTML = ajax.responseText;
          _("signupbtn").style.display = "block";
        } else {
          window.scrollTo(0,0);
          _("signupform").innerHTML = "OK "+u+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
        }
          }
        }
        ajax.send("f="+f+"&l="+l+"&u="+u+"&e="+e+"&p="+p1);
  }
}
function openTerms(){
  _("terms").style.display = "block";
  emptyElement("status");
}
 function addEvents(){
  _("elemID").addEventListener("click", func, false);
}
window.onload = addEvents; 
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
          <form name="signupform" id="signupform" onsubmit="return false;" class="box">
          	 <div class="field">
              <label for="" class="label">First name</label>
              <div class="control has-icons-left">
                <input id="fname" type="text" placeholder="First name" class="input" onfocus="emptyElement('status')"  maxlength="88">
                <span class="icon is-small is-left">
                  <i class="fa fa-envelope"></i>
                </span>
              </div>
            </div>
            <div class="field">
              <label for="" class="label">Last name</label>
              <div class="control has-icons-left">
                <input id="lname" type="text" placeholder="Last name" class="input" onfocus="emptyElement('status')"  maxlength="88">
                <span class="icon is-small is-left">
                  <i class="fa fa-envelope"></i>
                </span>
              </div>
            </div>
             <div class="field">
              <label for="" class="label">Username</label>
              <div class="control has-icons-left">
                <input id="username" type="text" placeholder="Username" class="input"  onblur="checkusername()" onkeyup="restrict('username')" maxlength="16">
                <span class="icon is-small is-left">
                  <i class="fa fa-envelope"></i>
                </span>
                <span id="unamestatus"></span>
              </div>
            </div>
             <div class="field">
              <label for="" class="label">Email</label>
              <div class="control has-icons-left">
                <input id="email" type="email" placeholder="e.g. bobsmith@gmail.com" class="input"  onblur="checkemail()" onkeyup="restrict('email')" maxlength="88">
                <span class="icon is-small is-left">
                  <i class="fa fa-envelope"></i>
                </span>
                <span id="emailstatus"></span>
              </div>
            </div>
            <div class="field">
              <label for="" class="label">Password</label>
              <div class="control has-icons-left">
                <input id="pass1" type="password" placeholder="*******" class="input" onfocus="emptyElement('status')" maxlength="16">
                <span class="icon is-small is-left">
                  <i class="fa fa-lock"></i>
                </span>
              </div>
            </div>
             <div class="field">
              <label for="" class="label">Confirm password</label>
              <div class="control has-icons-left">
                <input id="pass2" type="password" placeholder="*******" class="input" onfocus="emptyElement('status')" maxlength="16">
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
            <a href="#" onclick="return false" onmousedown="openTerms()">
 <h2>View the Terms Of Use</h2>
    </a>
    
    <div id="terms" style="display:none;">
     <div>alumniireland.ie Terms Of Use</div>
      <p><a href="#">Terms Of Use</a></p>
      </div>
    <br /><br />
            <div class="field">
               <button id="signupbtn" class="button is-info" onclick="signup()">Create account</button>
            </div>
             <span id="status"></span>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>	
</body>
</html>