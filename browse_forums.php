<?php

session_start();

$logged_in_user = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$section_output = '';

include_once("php_includes/db_conx.php");

	$sql = "SELECT * FROM sections ORDER BY section ASC";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query)>0){
    	$section_output .= '';
    	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $section_id = $row['sid'];
        $section_name = $row['section'];

        $section_output .= '<div class="columns is-multiline is-mobile">
  <div class="column is-one-third has-gap">
  <div class="box is-one-third ">
  <a href="section_view.php?sid='.$section_id.'"> '.$section_name.'</a>
  </div>
 </div></br></br>
</div>		 ';
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
<h1>Browse forums</h1>

<?php echo $section_output; ?></br>



</div>
</div>
</section>




</body>
</html>
