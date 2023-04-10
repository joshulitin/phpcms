<?php

if (ifItIsMethod("post")) {
  if (isset($_POST["login"])) {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
      loginUser($_POST["username"], $_POST["password"]);
    } else {
      $_SESSION["error"] = "Invalid credentials.";
    }
    $_SESSION["error"] = "Invalid credentials.";
  }
} ?>

<div class="col-md-4">            
    <!-- Blog Search Well -->
    <div class="well">
        <h4>Blog Search</h4>
        <form action="../../../../cms/search.php" method='post'>
        <div class="input-group">
            <input name = 'search' type="text" class="form-control">
            <span class="input-group-btn">
                <button name='submit' type = 'submit' class="btn btn-default" type="button">
                    <span class="glyphicon glyphicon-search"></span>
            </button>
            </span>
        </div>
        
        </form> <!-- Search Form -->
        <!-- /.input-group -->
    </div>


    <!--LOGIN FORM -->
    <div class="well">
    <?php if (isset($_SESSION["user_role"])): ?>
        <h4>Logged in as <?php echo $_SESSION["username"]; ?></h4>
        <a href="/cms/includes/logout.php" class="btn btn-primary">Logout</a>
    <?php else: ?>
        <h4>Login</h4>
        <form method='post'>
        <div class="form-group">
            <input name ="username" type="text" class="form-control" placeholder="Enter username">
        </div>
        <div class="input-group form-group">
            <input name ="password" type="password" class="form-control" placeholder="Enter password">
            <span class="input-group-btn">
                <button class="btn btn-primary" name="login" type="submit">Login</button>
            </span>
        </div>
        <?php if (isset($_SESSION["error"])) { ?>
        <p style="color: red;"><?= $_SESSION["error"] ?></p>
        <?php unset($_SESSION["error"]);} ?>
        <div class="form-group">
            <a href="/cms/forgot-password.php?forgot=<?php echo uniqid(
              true
            ); ?>">Forgot Password</a>
        </div>
        <div class="form-group">
            No account?
            <a href="/cms/registration">Register Here</a>
        </div>
        </form> <!-- Search Form -->

    <?php endif; ?>
        
        <!-- /.input-group -->
    </div>

    

    <!-- Blog Categories Well -->
    <div class="well">
    <?php
    $query = "SELECT * FROM categories";
    $selectAllCategoriesQuery = mysqli_query($connection, $query);
    ?>
        <h4>Blog Categories</h4>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">
                    <?php while (
                      $row = mysqli_fetch_assoc($selectAllCategoriesQuery)
                    ) {
                      $cat_title = $row["cat_title"];
                      $cat_id = $row["cat_id"];
                      echo "<li><a href='/cms/category/{$cat_id}'>{$cat_title}</a></li>";
                    } ?>
                    
                </ul>
            </div>
            <!-- /.col-lg-6 -->
            
        </div>
        <!-- /.row -->
    </div>

</div>