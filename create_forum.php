<?php

session_start();

$logged_in_user = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

$sid = $_GET['sid'];
$title = "";
$content = "";


if(isset($_POST["create"])){
 
  // GATHER THE POSTED DATA INTO LOCAL VARIABLES
  $user_id = $_POST['user_id'];
  $title = $_POST['title'];
  $content = $_POST['content'];
  $sid = $_POST['sid'];
 

 if($title && $content !=""){
  // CONNECT TO THE DATABASE
  include_once("php_includes/db_conx.php");
  // Add forum data into the database
  $sql = "INSERT INTO forums(createdby, title, content, sid, created_on) VALUES ('$user_id', '$title', '$content', '$sid', now())";

    $query = mysqli_query($db_conx, $sql);
    $forum_id = mysqli_insert_id($db_conx);

    if($forum_id){
    	header("location: ./forum_view.php?fid=$forum_id");
    } else {
    	echo "System error";
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
	

</head>

<body>
<?php include_once("page_top.php"); ?>
<section class="hero is-primary is-fullheight">
  <h1>Create forum post</h1>
  <div class="hero-body">

    <div class="container">
    	<div class="columns is-centered">
        <div class="column is-three-quarters">
          <form action="create_forum.php?sid=<?php echo $sid; ?>" method="post" class="box">
            <div class="field">
              <label for="" class="label">Title</label>
              <div class="control has-icons-left">
              	<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input name="title" type="text" placeholder="Title..." class="input" required>
              </div>
            </div>
            <div class="field">
              <label for="" class="label">Content</label>
              <div class="control has-icons-left">
              	<textarea name="content" class="textarea" rows = "10" placeholder="Ok <?php echo $logged_in_user; ?> lets create your content..."></textarea>
              	<input type="hidden" name="sid" value="<?php echo $sid; ?>">
              </div>
            </div>
            <div class="field">
              <input type ="submit" name="create" class="button is-primary" value="Post">  
            </div>
          </form>
        </div>
      </div>
</div>
</div>
</section>




</body>
</html>
