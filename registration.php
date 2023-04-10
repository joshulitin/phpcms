<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<!-- Navigation --> 
<?php include "includes/navigation.php"; ?>
     
<?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $firstname = trim($_POST["firstname"]);
  $lastname = trim($_POST["lastname"]);
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $error = [
    "firstname" => "",
    "lastname" => "",
    "username" => "",
    "email" => "",
    "password" => "",
  ]; // Username validation
  if ($firstname == "") {
    $error["firstname"] = "First name cannot be empty.";
  }
  if ($lastname == "") {
    $error["lastname"] = "Last name cannot be empty.";
  }
  if (strlen($username) < 5) {
    $error["username"] = "Minimum of 6 characters for username.";
  }
  if ($username == "") {
    $error["username"] = "Username cannot be empty.";
  }
  if (usernameExists($username)) {
    $error["username"] = "Username already exists.";
  } // Email validation
  if ($email == "") {
    $error["email"] = "Email cannot be empty.";
  }
  if (emailExists($email)) {
    $error["email"] = "Email already exists.";
  } // Password validation
  if ($password == "") {
    $error["password"] = "Password cannot be empty.";
  }
  if (strlen($password) < 5) {
    $error["password"] = "Minimum of 6 characters for password.";
  }
  foreach ($error as $key => $value) {
    if (empty($value)) {
      // loginUser($username, $password);
      unset($error[$key]);
    }
  } // End of foreach
  if (empty($error)) {
    echo "end2";
    registerUser($firstname, $lastname, $username, $email, $password);
    loginUser($username, $password);
  }
} ?>
    <!-- Page Content -->
    <div class="container">

<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                    
                <h1>Register</h1>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                    <div class="form-group">
                            <label for="firstname" class="sr-only">First Name</label>
                            <input type="text" name="firstname" id="username" class="form-control" 
                            placeholder="Enter your First Name" autocomplete="on" value="<?php echo isset(
                              $firstname
                            )
                              ? $firstname
                              : ""; ?>" >

                              <p><?php echo isset($error["firstname"])
                                ? $error["firstname"]
                                : ""; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="sr-only">Last Name</label>
                            <input type="text" name="lastname" id="username" class="form-control" 
                            placeholder="Enter your Last Name" autocomplete="on" value="<?php echo isset(
                              $lastname
                            )
                              ? $lastname
                              : ""; ?>" >

                              <p><?php echo isset($error["lastname"])
                                ? $error["lastname"]
                                : ""; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="username" class="sr-only">username</label>
                            <input type="text" name="username" id="username" class="form-control" 
                            placeholder="Enter Desired Username" autocomplete="on" value="<?php echo isset(
                              $username
                            )
                              ? $username
                              : ""; ?>" >

                              <p><?php echo isset($error["username"])
                                ? $error["username"]
                                : ""; ?></p>
                        </div>
                        
                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com" autocomplete="on" value="<?php echo isset(
                              $email
                            )
                              ? $email
                              : ""; ?>" >
                              <p><?php echo isset($error["email"])
                                ? $error["email"]
                                : ""; ?></p>
                        </div>
                         <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="Password">
                            <p><?php echo isset($error["password"])
                              ? $error["password"]
                              : ""; ?></p>
                            
                        </div>
                        <input type="submit" name="register" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Register">
                    </form>
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>
<hr>
<?php include "includes/footer.php"; ?>
