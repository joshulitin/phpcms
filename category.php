<?php include "includes/header.php"; ?>
<?php include "includes/db.php"; ?>

    <!-- Navigation -->
    <?php include "includes/navigation.php"; ?>

    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-8">
	<?php if (isset($_GET["category"])) {
   $post_category_id = $_GET["category"];
   if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "admin") {
     $query = "SELECT * FROM posts WHERE post_category_id = $post_category_id";
   } else {
     $query = "SELECT * FROM posts WHERE post_category_id = $post_category_id AND post_status = 'published'";
   }
   $selectAllPostsQuery = mysqli_query($connection, $query);
   if (mysqli_num_rows($selectAllPostsQuery) < 1) {
     echo "<h1> No post available </h1>";
   } else {
     while ($row = mysqli_fetch_assoc($selectAllPostsQuery)) {

       $post_id = $row["post_id"];
       $post_title = $row["post_title"];
       $post_user = $row["post_user"];
       $post_author = $row["post_author"];
       $post_date = $row["post_date"];
       $post_image = $row["post_image"];
       $post_content = substr($row["post_content"], 0, 100);
       ?>

	<!-- First Blog Post -->
	<h2>
		<a href="/cms/post/<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
	</h2>
	<p class="lead">
		by <a href="../author_post.php?author=<?php echo $post_user; ?>&p_id=<?php echo $post_id; ?>"><?php echo $post_user; ?></a>
	</p>
	<p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date; ?></p>
	<hr>
	<img class="img-responsive" src="../images/<?php echo $post_image; ?> " alt="">
	<hr>
	<p><?php echo $post_content; ?></p>
	<a class="btn btn-primary" href="/cms/post/<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

	<hr>

	<?php
     }
   }
 } else {
   header("Location: index.php");
 } ?>
                
            </div>

            <!-- Blog Sidebar Widgets Column --> 
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->
        <hr>
<?php include "includes/footer.php"; ?>
