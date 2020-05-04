<?php

session_start();

$logged_in_user = $_SESSION['username'];
$user_id = $_SESSION['user_id'];



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
<h1>Home page</h1>

<?php echo $logged_in_user; ?></br>
<?php echo $user_id; ?></br>
</div>
</div>
</section>




</body>
</html>
