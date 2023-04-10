<?php include "includes/admin_header.php"; ?>

<?php if (isset($_SESSION["username"])) {
  $username = $_SESSION["username"];
  $the_user_id = $_SESSION["user_id"];

  $query = "SELECT * FROM users WHERE username = '{$username}' ";

  $selectProfileQuery = mysqli_query($connection, $query);

  while ($row = mysqli_fetch_array($selectProfileQuery)) {
    $user_id = $row["user_id"];
    $username = $row["username"];
    $user_password = $row["user_password"];
    $user_firstname = $row["user_firstname"];
    $user_lastname = $row["user_lastname"];
    $user_email = $row["user_email"];
    $user_image = $row["user_image"];
  }
} ?>

<?php if (isset($_POST["edit_user"])) {
  // Error checking
  $user_firstname = trim($_POST["user_firstname"]);
  $user_lastname = trim($_POST["user_lastname"]);
  $username = trim($_POST["username"]);
  $user_email = trim($_POST["user_email"]);
  $user_password = trim($_POST["user_password"]);
  $error = [
    "user_firstname" => "",
    "user_lastname" => "",
    "username" => "",
    "user_email" => "",
    "user_password" => "",
  ]; // Username validation
  if ($user_firstname == "") {
    $error["user_firstname"] = "First name cannot be empty.";
  }
  if ($user_lastname == "") {
    $error["user_lastname"] = "Last name cannot be empty.";
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
  if ($user_email == "") {
    $error["user_email"] = "Email cannot be empty.";
  }
  if (emailExists($user_email)) {
    $error["user_email"] = "Email already exists.";
  } // Password validation
  if ($user_password == "") {
    $error["user_password"] = "Password cannot be empty.";
  }

  foreach ($error as $key => $value) {
    if (empty($value)) {
      // loginUser($username, $password);
      unset($error[$key]);
    }
  }

  // Post Edit User
  $user_firstname = $_POST["user_firstname"];
  $user_lastname = $_POST["user_lastname"];

  //   $post_image = $_FILES["post_image"]["name"];
  //   $post_image_temp = $_FILES["post_image"]["tmp_name"];

  $username = $_POST["username"];
  $user_email = $_POST["user_email"];
  $user_password = $_POST["user_password"];

  if (!empty($user_password)) {
    $queryPassword = "SELECT user_password FROM users WHERE user_id = $the_user_id";
    $getUserQuery = mysqli_query($connection, $queryPassword);
    confirmQuery($getUserQuery);

    $row = mysqli_fetch_array($getUserQuery);

    $the_user_password = $row["user_password"];

    if ($the_user_password != $user_password) {
      $hashed_password = password_hash($user_password, PASSWORD_BCRYPT, [
        "cost" => 12,
      ]);
    }

    $query = "UPDATE users SET ";
    $query .= "user_firstname = '{$user_firstname}', ";
    $query .= "user_lastname = '{$user_lastname}', ";
    $query .= "username = '{$username}', ";
    $query .= "user_email = '{$user_email}', ";
    $query .= "user_password = '{$hashed_password}' ";
    $query .= " WHERE username = '{$username}' ";

    $editUserQuery = mysqli_query($connection, $query);

    confirmQuery($editUserQuery);

    header("Location: index.php");
  }
} ?>
<div id="wrapper">
<!-- Navigation -->
<?php include "includes/admin_navigation.php"; ?>
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Welcome to Admin
					<small>Author</small>
				</h1>

				<form action="" method="post" enctype="multipart/form-data">

<!-- FORM -->
<div class="form-group">
	<label for="user_firstname">First Name</label>
	<input type="text" class="form-control" name="user_firstname" value="<?php echo $user_firstname; ?>">
  <p><?php echo isset($error["user_firstname"])
    ? $error["user_firstname"]
    : ""; ?></p>
</div>
<div class="form-group">
	<label for="user_lastname">Last Name</label>
	<input type="text" class="form-control" name="user_lastname" value="<?php echo $user_lastname; ?>">
  <p><?php echo isset($error["user_lastname"])
    ? $error["user_lastname"]
    : ""; ?></p>
</div>

<!-- CATEGORIES -->


<!-- END CATEGORIES -->
<!-- 
<div class="form-group">
	<label for="post_image">Post Image</label>
	<input type="file" class="form-control" name="image">
</div> -->

<div class="form-group">
	<label for="username">Username</label>
	<input type="text" class="form-control" name="username" value="<?php echo $username; ?>">
  <p><?php echo isset($error["username"]) ? $error["username"] : ""; ?></p>
<div class="form-group">
	<label for="user_email">Email</label>
	<input type="email" class="form-control" name="user_email" value="<?php echo $user_email; ?>">
  <p><?php echo isset($error["user_email"]) ? $error["user_email"] : ""; ?></p>
</div>
<div class="form-group">
	<label for="user_password">Password</label>
	<input autocomplete="off" type="password" class="form-control" name="user_password">
  <p><?php echo isset($error["user_password"])
    ? $error["user_password"]
    : ""; ?></p>
</div>
<div class="form-group">
	<input type="submit" class="btn btn_primary" name="edit_user" value="Update Profile">
</div>

</div>
</form>
			</div>
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<?php include "includes/admin_footer.php"; ?>
