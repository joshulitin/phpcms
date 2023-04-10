<?php
if (isset($_POST["post"])) {
  $query = "SELECT * FROM users ";
  $select_users = mysqli_query($connection, $query);
  confirmQuery($select_users);

  while ($row = mysqli_fetch_assoc($select_users)) {
    $user_id = $row["user_id"];
  }
  $post_title = escape($_POST["post_title"]);
  $post_category_id = escape($_POST["post_category"]);
  $user_id = escape($_POST["user_id"]);
  $post_user = escape($_POST["post_user"]);
  $post_status = escape($_POST["post_status"]);
  $post_image = $_FILES["post_image"]["name"];
  $post_image_temp = $_FILES["post_image"]["tmp_name"];
  $post_tags = escape($_POST["post_tags"]);
  $post_content = escape($_POST["post_content"]);
  $post_date = escape(date("d-m-y"));
  //   $post_comment_count = 4;

  move_uploaded_file($post_image_temp, "../images/$post_image");

  $query =
    "INSERT INTO posts(post_category_id, user_id, post_title, post_user, post_date, post_image, post_content, post_tags, post_status) ";

  $query .= "VALUES({$post_category_id}, '{$user_id}', '{$post_title}', '{$post_user}', now(), '{$post_image}', '{$post_content}', '{$post_tags}', '{$post_status}')";

  $create_post_query = mysqli_query($connection, $query);

  confirmQuery($create_post_query);

  redirect("posts.php");
} ?>

<form action="" method="post" enctype="multipart/form-data">

<div class="form-group">
	<label for="post_title">Post Title</label>
	<input type="text" class="form-control" name="post_title">
</div>
<!-- CATEGORIES -->
<div class="form-group">
<label for="post_title">Category</label>
	<select name="post_category" id="post_category">
	<?php
 $query = "SELECT * FROM categories";
 $selectCategoriesId = mysqli_query($connection, $query);
 confirmQuery($selectCategoriesId);

 while ($row = mysqli_fetch_assoc($selectCategoriesId)) {
   $cat_id = $row["cat_id"];
   $cat_title = $row["cat_title"];

   echo "<option value='{$cat_id}'>{$cat_title}</option>";
 }
 ?>
	
	</select>
</div>
<!-- END CATEGORIES -->

<div class="form-group">
<label for="post_title">User</label>
	
	<?php if (isset($_SESSION["username"])) {
   $username = $_SESSION["username"];
 } ?>

	<input type="text" class="form-control" name="post_user" value="<?php echo $username; ?>" readonly>
</div>


           
<div class="form-group">
<label for="post_title">User ID</label>
	
	<?php if (isset($_SESSION["user_id"])) {
   $user_id = $_SESSION["user_id"];
 } ?>

	<input type="text" class="form-control" name="user_id" value="<?php echo $user_id; ?>" readonly>
</div>


<!-- <div class="form-group">
	<label for="post_author">Post Author</label>
	<input type="text" class="form-control" name="post_author">
</div> -->
<div class="form-group">
	<label for="post_status">Post Status</label>
  <select name="post_status" id="">
    <option value="draft">Select Options</option>
    <option value="published">published</option>
    <option value="draft">draft</option>
  </select>
	<!-- <input type="text" class="form-control" name="post_status"> -->
</div>
<div class="form-group">
	<label for="post_image">Post Image</label>
	<input type="file" class="form-control" name="post_image">
</div>
<div class="form-group">
	<label for="post_tags">Post Tags</label>
	<input type="text" class="form-control" name="post_tags">
<div class="form-group">
	<label for="summernote">Post Content</label>
	<textarea class="form-control" name="post_content" id="" cols="30" rows="10"></textarea>
</div>
<div class="form-group">
	<input type="submit" class="btn btn_primary" name="post" value="Publish Post">
</div>

</div>
</form>