<?php
if (isset($_POST["create_user"])) {
  $user_firstname = escape($_POST["user_firstname"]);
  $user_lastname = escape($_POST["user_lastname"]);
  $user_role = escape($_POST["user_role"]);

  //   $post_image = $_FILES["post_image"]["name"];
  //   $post_image_temp = $_FILES["post_image"]["tmp_name"];

  $username = escape($_POST["username"]);
  $user_email = escape($_POST["user_email"]);
  $user_password = escape($_POST["user_password"]);

  $user_password = password_hash($user_password, PASSWORD_BCRYPT, [
    "cost" => 10,
  ]);
  //   $post_date = date("d-m-y");
  //   $post_comment_count = 4;

  //   move_uploaded_file($post_image_temp, "../images/$post_image");

  $query =
    "INSERT INTO users(user_firstname, user_lastname, user_role, username, user_email, user_password) ";

  $query .= "VALUES ('{$user_firstname}', '{$user_lastname}', '{$user_role}', '{$username}', '{$user_email}', '{$user_password}')";

  $createUserQuery = mysqli_query($connection, $query);

  confirmQuery($createUserQuery);

  redirect("users.php");
} ?>

<form action="" method="post" enctype="multipart/form-data">

<div class="form-group">
	<label for="user_firstname">First Name</label>
	<input type="text" class="form-control" name="user_firstname">
</div>
<div class="form-group">
	<label for="user_lastname">Last Name</label>
	<input type="text" class="form-control" name="user_lastname">
</div>

<!-- CATEGORIES -->
<div class="form-group">
<select name="user_role" id="post_category">
	<option value="subscriber">Select Options</option>
	<option value="admin">Admin</option>	
	<option value="subscriber">Subscriber</option>
</select>
</div>

<!-- END CATEGORIES -->
<!-- 
<div class="form-group">
	<label for="post_image">Post Image</label>
	<input type="file" class="form-control" name="image">
</div> -->

<div class="form-group">
	<label for="username">Username</label>
	<input type="text" class="form-control" name="username">
<div class="form-group">
	<label for="user_email">Email</label>
	<input type="email" class="form-control" name="user_email">
</div>
<div class="form-group">
	<label for="user_password">Password</label>
	<input type="password" class="form-control" name="user_password">
</div>
<div class="form-group">
	<input type="submit" class="btn btn_primary" name="create_user" value="Add User">
</div>

</div>
</form>