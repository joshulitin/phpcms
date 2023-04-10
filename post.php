<?php include "includes/header.php"; ?>
<?php include "includes/db.php"; ?>


<!-- Navigation -->
<?php include "includes/navigation.php"; ?>

<?php // Like Query


if (isset($_POST["liked"])) {
  $post_id = $_POST["post_id"];
  $user_id = $_POST["user_id"];
  $query = "SELECT * FROM posts WHERE post_id = $post_id";
  $postResult = mysqli_query($connection, $query);
  $post = mysqli_fetch_array($postResult);
  $likes = $post["likes"];
  mysqli_query(
    $connection,
    "UPDATE posts SET likes=$likes+1 WHERE post_id=$post_id"
  );
  mysqli_query(
    $connection,
    "INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)"
  );
  exit();
} // Unlike Query
if (isset($_POST["unliked"])) {
  $post_id = $_POST["post_id"];
  $user_id = $_POST["user_id"];
  $query = "SELECT * FROM posts WHERE post_id = $post_id";
  $postResult = mysqli_query($connection, $query);
  $post = mysqli_fetch_array($postResult);
  $likes = $post["likes"];
  mysqli_query(
    $connection,
    "DELETE FROM likes WHERE post_id = $post_id AND user_id = $user_id"
  );
  mysqli_query(
    $connection,
    "UPDATE posts SET likes = $likes-1 WHERE post_id =  $post_id"
  );
  exit();
}
?>
<!-- Page Content -->
<div class="container">
<div class="row">
    <!-- Blog Entries Column -->
    <div class="col-md-8">
        <?php if (isset($_GET["p_id"])) {
          $the_post_id = $_GET["p_id"];
          $viewQuery = "UPDATE posts SET post_view_count = post_view_count + 1 WHERE post_id = $the_post_id";
          $sendQuery = mysqli_query($connection, $viewQuery);
          if (!$sendQuery) {
            die("Query Failed. " . mysqli_error($connection));
          }
          if (
            isset($_SESSION["user_role"]) &&
            $_SESSION["user_role"] == "admin"
          ) {
            $query = "SELECT * FROM posts WHERE post_id = $the_post_id";
          } else {
            $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status = 'published' ";
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
              $post_content = $row["post_content"];
              ?>


        <!-- First Blog Post -->
        <h2>
            <a href="#"><?php echo $post_title; ?></a>
        </h2>
        <p class="lead">
            by <a href="../author_post.php?author=<?php echo $post_user; ?>&p_id=<?php echo $post_id; ?>"><?php echo $post_user; ?></a>
        </p>
        <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date; ?></p>
        <hr>
        <a href="<?php echo $post_id; ?>">
       
        <img class="img-responsive" src="../images/<?php echo $post_image; ?> " alt="">
         </a>
        <hr>
        <p><?php echo $post_content; ?></p>
        <hr>
        
        <?php if (isLoggedIn()) { ?>
          
        <div class="row">
        <p class="pull-right"><a
          class="<?php echo userLiked($the_post_id) ? "unlike" : "like"; ?>"
          href=""><span class="glyphicon glyphicon-thumbs-<?php echo userLiked(
            $the_post_id
          )
            ? "down"
            : "up"; ?>"></span>
          <?php echo userLiked($the_post_id) ? " Unlike" : " Like"; ?></a></p>
        </div>

          <?php } else { ?>
            <div class="row">
              <p class="pull-right">You need to <a href="/cms/login.php">Login</a> first.</p>
            </div>
            <?php } ?>
        
        
        <div class="row">
          <p class="pull-right">Likes: <?php getPostLikes($the_post_id); ?></p>
        </div>

        <div class="clearfix"></div>
        <?php
            } ?>
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
            } /* $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 WHERE post_id = $the_post_id";
             $update_comment_count = mysqli_query($connection, $query);
             */
          } else {
            echo "<script>alert('Fields cannot be empty.')</script>";
          }
          echo "<script>alert('Comment submitted.')</script>";
          redirect("/cms/post/$the_post_id");
        } ?>
        <!-- Comments Form -->
        <div class="well">
            <h4>Leave a Comment:</h4>
            <form action="" method="post" role="form">
                
                <div class="form-group">
                    <label for="Author">Author</label>
                    <input type="text" class="form-control" name="comment_author">
                </div>

                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" class="form-control" name="comment_email">
                </div>

                <div class="form-group">
                    <label for="Your Comment">Comment</label>
                    <textarea class="form-control" rows="3" name="comment_content"></textarea>
                </div>
                <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
            </form>
        </div>

        <hr>

        <!-- Posted Comments -->
        <?php
        $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
        $query .= "AND comment_status = 'approved' ";
        $query .= "ORDER BY comment_id DESC ";
        $select_comment_query = mysqli_query($connection, $query);
        if (!$select_comment_query) {
          die("QUERY FAILED" . mysqli_error($connection));
        }
        while ($row = mysqli_fetch_array($select_comment_query)) {

          $comment_date = $row["comment_date"];
          $comment_content = $row["comment_content"];
          $comment_author = $row["comment_author"];
          ?>

        <!-- Comment -->
        <div class="media">
            <a class="pull-left" href="#">
                <img class="media-object" src="http://placehold.it/64x64" alt="">
            </a>
            <div class="media-body">
                <h4 class="media-heading"><?php echo $comment_author; ?>
                    <small><?php echo $comment_date; ?></small>
                </h4>
                <?php echo $comment_content; ?>
                </div>
            </div>
        
        <?php
        }

          }
        } else {
          redirect("index.php");
        } ?>
        </div>

    <!-- Blog Sidebar Widgets Column --> 
    <?php include "includes/sidebar.php"; ?>

</div>
<!-- /.row -->
<hr>
<?php include "includes/footer.php"; ?>

<script>
    $(document).ready(function(){

      var post_id = <?php echo $post_id; ?>;
      var user_id = <?php echo loggedInUserId(); ?>;
      // Like function
      $('.like').click(function(){
        $.ajax({
          url: "/cms/post/<?php echo $the_post_id; ?>",
          type: 'post',
          data: {
            'liked': 1,
            'post_id': post_id,
            'user_id': user_id

          }
        });
      });

       // Unlike function
       $('.unlike').click(function(){
        $.ajax({
          url: "/cms/post/<?php echo $the_post_id; ?>",
          type: 'post',
          data: {
            'unliked': 1,
            'post_id': post_id,
            'user_id': user_id

          }
        });
      });
    })
</script>