<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
      </button>
  
      <a class="navbar-brand" href="/cms"><i class="fa fa-home"></i> Home</a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

      <?php if (isLoggedIn()): ?>
        
        <li>
            <a href="/cms/admin"><i class="fa fa-dashboard"></i> Dashboard</a>
        </li>

      <?php endif; ?>
        
        
        
       
                    
        <?php if (isset($_SESSION["user_role"])) {
          if (isset($_GET["p_id"])) {
            $the_post_id = $_GET["p_id"];
            echo "<li><a href='/cms/admin/posts.php?source=edit_post&p_id={$the_post_id}'>Edit Post</a></li>";
          }
        } ?>   
      </ul>
      <?php if (isLoggedIn()): ?>
      <ul class="nav navbar-right top-nav navbar-nav">
        
            <!-- <li> <a class='navbar-brand' href=''>User Online: </a></li> -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> 
                    <?php if (isset($_SESSION["username"])) {
                      echo $_SESSION["username"];
                    } ?>
                    <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="/cms/admin/profile.php"><i class="fa fa-fw fa-file"></i> Profile</a>
                        </li>
                        
                        <li class="divider"></li>
                        <li>
                          <a href="/cms/contact"><i class="fa fa-envelope"></i> Contact Me</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                          <a href="/cms/includes/logout.php"><i class="fa fa-fw fa-power-off"></i> Logout</a>
                        </li>
                        
                    </ul>
                </li>
            </ul>
      <?php endif; ?>
      <!-- END OF FIRST NAV -->
        
    </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
<hr>