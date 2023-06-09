<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Id</th>
			<th>Author</th>
			<th>Comment</th>
			<th>Email</th>
			<th>Status</th>
			<th>In Response to</th>
			<th>Date</th>
			<th>Approve</th>
			<th>Unapprove</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
	<tr>
			
	<?php
 $query = "SELECT * FROM comments";

 $selectComments = mysqli_query($connection, $query);

 while ($row = mysqli_fetch_assoc($selectComments)) {
   $comment_id = $row["comment_id"];
   $comment_post_id = $row["comment_post_id"];
   $comment_author = $row["comment_author"];
   $comment_content = $row["comment_content"];
   $comment_email = $row["comment_email"];
   $comment_status = $row["comment_status"];
   $comment_date = $row["comment_date"];

   echo "<tr>";

   echo "<td>{$comment_id}</td>";
   echo "<td>{$comment_author}</td>";
   echo "<td>{$comment_content}</td>";

   //  $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id} ";
   //  $selectCategoriesId = mysqli_query($connection, $query);

   //  while ($row = mysqli_fetch_assoc($selectCategoriesId)) {
   //      $cat_id = $row['cat_id'];
   //      $cat_title = $row['cat_title'];
   //  }
   echo "<td>{$comment_email}</td>";
   echo "<td>{$comment_status}</td>";

   $query = "SELECT * FROM posts WHERE post_id = $comment_post_id";
   $selectPostIdQuery = mysqli_query($connection, $query);

   while ($row = mysqli_fetch_assoc($selectPostIdQuery)) {
     $post_id = $row["post_id"];
     $post_title = $row["post_title"];

     echo "<td><a href='../post/$post_id'>$post_title</a></td>";
   }
   echo "<td>{$comment_date}</td>";
   echo "<td><a class='btn btn-primary' href='comments.php?approve={$comment_id}'>Approve</a></td>";
   echo "<td><a class='btn btn-info' href='comments.php?unapprove={$comment_id}'>Unapprove</a></td>";
   echo "<td><a class='btn btn-danger' href='comments.php?delete={$comment_id}'>Delete</a></td>";
   //  echo "<td><a href='posts.php?source=edit_post&p_id='>Edit</a></td>";
   echo "</tr>";
 }
 ?>
								
	</tr>
	</tbody>
</table>

<?php
if (isset($_GET["approve"])) {
  $the_comment_id = escape($_GET["approve"]);

  $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id = $the_comment_id";

  $approveCommentQuery = mysqli_query($connection, $query);

  redirect("comments.php");
}

if (isset($_GET["unapprove"])) {
  $the_comment_id = escape($_GET["unapprove"]);

  $query = "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id = $the_comment_id";

  $unapproveCommentQuery = mysqli_query($connection, $query);

  redirect("comments.php");
}

if (isset($_GET["delete"])) {
  $the_comment_id = escape($_GET["delete"]);

  $query = "DELETE FROM comments WHERE comment_id = {$the_comment_id}";

  $deleteQuery = mysqli_query($connection, $query);

  redirect("comments.php");
}


?>
