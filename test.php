<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php include "admin/functions.php"; ?>

<?php
echo loggedInUserId();
if (userLiked(1)) {
  echo "USER LIKED IT";
} else {
  echo "DID NOT";
}
 ?>
