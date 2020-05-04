<?php

session_start();

$fid = $_GET['fid'];
$forum_output = '';

include_once("php_includes/db_conx.php");

  $sql = "SELECT * FROM forums WHERE fid='$fid'";
  
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query)>0){
      while($row = mysqli_fetch_array($query)){
        $fid = $row['fid'];
        $createdby = $row['createdby'];
        $title = $row['title'];
        $content = $row['content'];
        $sid = $row['sid'];
        $created_on = $row['created_on'];

        $sql2 = "SELECT username FROM users WHERE id='$createdby'";
  
    $query2 = mysqli_query($db_conx, $sql2);
    if(mysqli_num_rows($query2)>0){
      while($row = mysqli_fetch_array($query2)){
        $username = $row['username'];
    }
}

        $forum_output = '<div class="box">
  <article class="media">
    <div class="media-left">
      <figure class="image is-64x64">
        <img src="https://bulma.io/images/placeholders/128x128.png" alt="Image">
      </figure>
    </div>
    <div class="media-content">
      <div class="content">
        <p>
          <strong>'.$title.'</strong> <small>'.$username.'</small> <small>31m</small>
          <br>
          '.$content.'
        </p>
      </div>
      <nav class="level is-mobile">
        <div class="level-left">
          <a class="level-item" aria-label="reply">
            <span class="icon is-small">
              <i class="fa fa-reply" aria-hidden="true"></i>
            </span>
          </a>
          <a class="level-item" aria-label="retweet">
            <span class="icon is-small">
              <i class="fa fa-retweet" aria-hidden="true"></i>
            </span>
          </a>
          <a class="level-item" aria-label="like">
            <span class="icon is-small">
              <i class="fa fa-heart" aria-hidden="true"></i>
            </span>
          </a>
        </div>
      </nav>
    </div>
  </article>
</div>';


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
<h1>Forums view</h1>

<?php echo $forum_output; ?>

 





</div>
</div>
</section>




</body>
</html>
