<?php
if (isset($_SESSION["username"])) {
  $user_role = $_SESSION["user_role"];
  $username = $_SESSION["username"];
  $user_id = $_SESSION["user_id"];
}
if (isset($_POST["checkBoxArray"])) {
  foreach ($_POST["checkBoxArray"] as $postValueId) {
    $bulk_options = $_POST["bulk_options"];

    switch ($bulk_options) {
      case "published":
        $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id={$postValueId}";
        $updatePostStatus = mysqli_query($connection, $query);
        confirmQuery($updatePostStatus);
        break;
      case "draft":
        $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id={$postValueId}";
        $updatePostStatus = mysqli_query($connection, $query);
        confirmQuery($updatePostStatus);
        break;
      case "delete":
        $query = "DELETE FROM posts WHERE post_id={$postValueId}";
        $updateDeletePost = mysqli_query($connection, $query);
        confirmQuery($updateDeletePost);
        break;
    }
  }
}
?>

<form action="" method="post">

<table class="table table-bordered table-hover">

  <div id="bulkOptionsContainer" class="col-xs-4" style="padding-bottom: 1%">
    <select class="form-control" name="bulk_options" id="">
      <option value="">Select Options</option>
      <option value="published">Publish</option>
      <option value="draft">Draft</option>
      <option value="delete">Delete</option>
    </select>
  </div>

  <div class="col-xs-4">
    <input type="submit" name="submit" class="btn btn-success" value="Apply">
    <a href="add_post.php" class="btn btn-primary">Add New</a>
  </div>
	<thead>
		<tr>
      <th><input id="selectAllBoxes" type="checkbox"></th>
			<th>Id</th>
			<th>User</th>
			<th>Title</th>
			<th>Category</th>
			<th>Status</th>
			<th>Image</th>
			<th>Tags</th>
			<th>Comments</th>
			<th>Date</th>
      <th>View Post</th>
      <th>Edit</th>
			<th>Delete</th>
			<th>View Count</th>
		</tr>
	</thead>
	<tbody>
	<tr>
			
	<?php
 //  $query = "SELECT * FROM posts";
 if (!isAdmin()) {
   $query =
     "SELECT posts.post_id, posts.post_author, posts.post_user, posts.post_title, posts.post_category_id, posts.post_status, posts.post_image, ";
   $query .=
     "posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_view_count, categories.cat_id, categories.cat_title FROM posts ";
   $query .= "LEFT JOIN categories ON posts.post_category_id = categories.cat_id WHERE posts.user_id = $user_id ORDER BY posts.post_id DESC";
 } else {
   $query =
     "SELECT posts.post_id, posts.post_author, posts.post_user, posts.post_title, posts.post_category_id, posts.post_status, posts.post_image, ";
   $query .=
     "posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_view_count, categories.cat_id, categories.cat_title FROM posts ";
   $query .=
     "LEFT JOIN categories ON posts.post_category_id = categories.cat_id ORDER BY posts.post_id DESC";
 }
 $selectPosts = mysqli_query($connection, $query);

 while ($row = mysqli_fetch_assoc($selectPosts)) {

   $post_id = $row["post_id"];
   $post_author = $row["post_author"];
   $post_user = $row["post_user"];
   $post_title = $row["post_title"];
   $post_category_id = $row["post_category_id"];
   $post_status = $row["post_status"];
   $post_image = $row["post_image"];
   $post_tags = $row["post_tags"];
   $post_comment_count = $row["post_comment_count"];
   $post_date = $row["post_date"];
   $post_view_count = $row["post_view_count"];
   $cat_id = $row["cat_id"];
   $cat_title = $row["cat_title"];
   echo "<tr>";
   ?>
    <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id; ?>'></td>

   <?php
   echo "<td>{$post_id}</td>";

   if (!empty($post_author)) {
     echo "<td>$post_author</td>";
   } elseif (!empty($post_user)) {
     echo "<td>$post_user</td>";
   }

   echo "<td>{$post_title}</td>";

   //  $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id} ";
   //  $selectCategoriesId = mysqli_query($connection, $query);

   //  while ($row = mysqli_fetch_assoc($selectCategoriesId)) {
   //    $cat_id = $row["cat_id"];
   //    $cat_title = $row["cat_title"];
   //  }
   echo "<td>{$cat_title}</td>";

   echo "<td>{$post_status}</td>";
   echo "<td><img width='100' src='../images/$post_image'></td>";
   echo "<td>{$post_tags}</td>";

   $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
   $sendCommentQuery = mysqli_query($connection, $query);

   $row = mysqli_fetch_array($sendCommentQuery);
   $comment_id = isset($row["comment_id"]);
   $countComments = mysqli_num_rows($sendCommentQuery);

   echo "<td><a href='post_comments.php?id=$post_id'>$countComments</a></td>";

   echo "<td>{$post_date}</td>";
   echo "<td><a class='btn btn-info' href='../post/{$post_id}'>View Post</a></td>";
   echo "<td><a class='btn btn-success' href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
   ?>
  <form action="" method="post">
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
    <?php echo "<td><input class='btn btn-danger' type='submit' name='delete' value='Delete'></td>"; ?>
  </form>
  <?php
  // echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete this post?'); \"href='posts.php?delete={$post_id}'>Delete</a></td>";

  echo "<td>{$post_view_count}</td>";

  echo "</tr>";

 }
 ?>
	


	</tr>
	</tbody>
</table>

</form>

<?php if (isset($_POST["delete"])) {
  $the_post_id = $_POST["post_id"];

  $query = "DELETE FROM posts WHERE post_id = {$the_post_id}";

  $deleteQuery = mysqli_query($connection, $query);

  redirect("posts.php");
}
?>
