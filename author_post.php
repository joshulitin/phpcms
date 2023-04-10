<?php include "includes/header.php"; ?>
<?php include "includes/db.php"; ?>

<!-- Navigation -->
<?php include "includes/navigation.php"; ?>

<!-- Page Content -->
<div class="container">
<div class="row">
    <!-- Blog Entries Column -->
    <div class="col-md-8">
        
        <?php
        if (isset($_GET["p_id"])) {
          $the_post_id = $_GET["p_id"];
          $the_post_author = $_GET["author"];
        }
        $query = "SELECT * FROM posts WHERE post_user = '{$the_post_author}'";
        $selectAllPostsQuery = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($selectAllPostsQuery)) {

          $post_id = $row["post_id"];
          $post_title = $row["post_title"];
          $post_author = $row["post_user"];
          $post_date = $row["post_date"];
          $post_image = $row["post_image"];
          $post_content = $row["post_content"];
          ?>

        
        <!-- First Blog Post -->
        <h2>
            <a href="post/<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
        </h2>
        <p class="lead">
            by <a href="author_post.php?author=<?php echo $post_author; ?>&p_id=<?php echo $post_id; ?>"><?php echo $post_author; ?></a>
        </p>
        <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date; ?></p>
        <hr>
        <img class="img-responsive" src="images/<?php echo $post_image; ?> " alt="">
        <hr>
        <p><?php echo $post_content; ?></p>
        

        <hr>

        <?php
        }
        ?>

        <!-- Blog Comments -->

        <?php if (isset($_POST["create_comment"])) {
          $the_post_id = escape($_GET["p_id"]);
          $comment_author = escape($_POST["comment_author"]);
          $comment_email = escape($_POST["comment_email"]);
          $comment_content = escape($_POST["comment_content"]);
          if (
            !empty($comment_author) &&
            !empty($comment_email) &&
            !empty($comment_content)
          ) {
            $query =
              "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";
            $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved', now())";
            $create_comment_query = mysqli_query($connection, $query);
            if (!$create_comment_query) {
              die('QUERY FAILED. mysqli_error($connection)');
            }
            $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 WHERE post_id = $the_post_id";
            $update_comment_count = mysqli_query($connection, $query);
          } else {
            echo "<script>alert('Fields cannot be empty.')</script>";
          }
        } ?>
        <!-- Comments Form -->

        <!-- Posted Comments -->
        </div>

    <!-- Blog Sidebar Widgets Column --> 
    <?php include "includes/sidebar.php"; ?>

</div>
<!-- /.row -->
<hr>
<?php include "includes/footer.php"; ?>
