<?php
if (isset($_GET["p_id"])) {
  $the_post_id = escape($_GET["p_id"]);
}

$query = "SELECT * FROM posts WHERE post_id = $the_post_id";
$select_posts_by_id = mysqli_query($connection, $query);

while ($row = mysqli_fetch_assoc($select_posts_by_id)) {
  $post_id = $row["post_id"];
  $post_user = $row["post_user"];
  $post_title = $row["post_title"];
  $user_id = $row["user_id"];
  $post_category_id = $row["post_category_id"];
  $post_status = $row["post_status"];
  $post_image = $row["post_image"];
  $post_content = $row["post_content"];
  $post_tags = $row["post_tags"];
  $post_comment_count = $row["post_comment_count"];
}

if (isset($_POST["post"])) {
  $post_user = escape($_POST["post_user"]);
  $post_title = escape($_POST["post_title"]);
  $user_id = escape($_POST["user_id"]);
  $post_category_id = escape($_POST["post_category"]);
  $post_status = escape($_POST["post_status"]);
  $post_image = $_FILES["post_image"]["name"];
  $post_image_temp = $_FILES["post_image"]["tmp_name"];
  $post_content = escape($_POST["post_content"]);
  $post_tags = escape($_POST["post_tags"]);

  move_uploaded_file($post_image_temp, "../images/$post_image");

  if (empty($post_image)) {
    $query = "SELECT * FROM posts WHERE post_id = $the_post_id";

    $select_image = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_array($select_image)) {
      $post_image = $row["post_image"];
    }
  }

  $query = "UPDATE posts SET ";
  $query .= "post_title = '{$post_title}', ";
  $query .= "post_category_id = '{$post_category_id}', ";
  $query .= "post_user = '{$post_user}', ";
  $query .= "user_id = '{$user_id}', ";
  $query .= "post_date = now(), ";
  $query .= "post_status = '{$post_status}', ";
  $query .= "post_tags = '{$post_tags}', ";
  $query .= "post_content = '{$post_content}', ";
  $query .= "post_image = '{$post_image}' ";
  $query .= "WHERE post_id = {$the_post_id} ";

  $update_post = mysqli_query($connection, $query);

  confirmQuery($update_post);
  redirect("posts.php");
}
?>

<form action="" method="post" enctype="multipart/form-data">

<div class="form-group">
	<label for="post_title">Post Title</label>
	<input type="text" class="form-control" name="post_title" value="<?php echo $post_title; ?>"> 
</div>

<div class="form-group">
<label for="post_category">Categories</label>
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

<div class="form-group">
<label for="post_status">Post Status</label>
<select name="post_status" id="">
    <option value="<?php echo $post_status; ?>"><?php echo $post_status; ?></option>
    <?php if ($post_status === "published") {
      echo "<option value='draft'>draft</option>";
    } else {
      echo "<option value='published'>published</option>";
    } ?>
</select>
</div>
<div class="form-group">
	<label for="post_user">Post User</label>
	<input type="text" class="form-control" name="post_user" value="<?php echo $post_user; ?>" readonly>
</div>
<div class="form-group">
	<label for="post_user">User ID</label>
	<input type="text" class="form-control" name="user_id" value="<?php echo $user_id; ?>" readonly>
</div>
<div class="form-group">
	<img width="100" src="../images/<?php echo $post_image; ?>" alt="">
	<label for="post_image">Post Image</label>
	<input type="file" class="form-control" name="post_image">
</div>
<div class="form-group">
	<label for="post_tags">Post Tags</label>
	<input type="text" class="form-control" name="post_tags" value="<?php echo $post_tags; ?>">
<div class="form-group">
	<label for="post_content">Post Content</label>
	<textarea class="form-control" name="post_content" id="" cols="30" rows="10" ><?php echo $post_content; ?></textarea>
</div>
<div class="form-group">
	<input type="submit" class="btn btn_primary" name="post" value="Publish Post">
</div>

</div>
</form>