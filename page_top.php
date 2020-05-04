<?php


if($_SESSION['username']){
  $login_links = '<a href="logout.php" class="button is-primary">
            Logout
          </a>';
} else  {
  $login_links = '<a href="register.php" class="button is-primary">
           Sign up
          </a>
          <a href="login.php" class="button is-primary">
            Login
          </a>';
}

?>

<nav class="navbar has-shadow" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="https://bulma.io">
      <img src="./images/alumniireland_logo.png" width="168" height="28">
    </a>

    <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
      <a href = "home.php" class="navbar-item">
        Home
      </a>

      <a href="browse_forums.php" class="navbar-item">
        Browse forums
      </a>

      <a href="#" class="navbar-item">
        New forum
      </a>

      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          More
        </a>

        <div class="navbar-dropdown">
          <a class="navbar-item">
            About
          </a>
          <a class="navbar-item">
            Jobs
          </a>
          <a class="navbar-item">
            Contact
          </a>
          <hr class="navbar-divider">
          <a class="navbar-item">
            Report an issue
          </a>
        </div>
      </div>
    </div>

    <div class="navbar-end">
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          Account
        </a>


        <div class="navbar-dropdown">
          <a class="navbar-item">
            About
          </a>
          <a class="navbar-item">
            Jobs
          </a>
          <a class="navbar-item">
            Contact
          </a>
          <hr class="navbar-divider">
          <a class="navbar-item">
            Report an issue
          </a>
        </div>
      </div>
      <div class="navbar-item">
     <div class="buttons">
          <?php echo $login_links; ?>
          
        </div>
        
      </div>
    </div>
  </div>
</nav>