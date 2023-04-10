<?php
//===== DATABASE HELPER FUNCTIONS =====//

function query($query)
{
  global $connection;
  $result = mysqli_query($connection, $query);
  confirmQuery($result);
  return $result;
}

function fetchRecords($result)
{
  return mysqli_fetch_array($result);
}

function countRecords($result)
{
  return mysqli_num_rows($result);
}
//***** END DATABASE HELPER FUNCTIONS *****//

//===== GENERAL HELPER FUNCTIONS =====//
function getUsername()
{
  return isset($_SESSION["username"]) ? $_SESSION["username"] : null;
}

//***** END GENERAL HELPER FUNCTIONS *****//

//===== USER SPECIFIC HELPER FUNCTIONS =====//

function getUserPost()
{
  return query("SELECT * FROM posts WHERE user_id =" . loggedInUserId() . "");
}

function getUserComments()
{
  return query(
    "SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id 
    WHERE user_id =" .
      loggedInUserId() .
      ""
  );
}

function getUserCategories()
{
  return query(
    "SELECT * FROM categories WHERE user_id=" . loggedInUserId() . ""
  );
}

function getUserPublishPost()
{
  return query(
    "SELECT * FROM posts WHERE user_id =" .
      loggedInUserId() .
      " AND post_status='published'"
  );
}

function getUserDraftPost()
{
  return query(
    "SELECT * FROM posts WHERE user_id =" .
      loggedInUserId() .
      " AND post_status='draft'"
  );
}

function getUserApproveComments()
{
  return query(
    "SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id 
    WHERE user_id =" .
      loggedInUserId() .
      " AND comment_status='approved'"
  );
}
function getUserUnapproveComments()
{
  return query(
    "SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id 
    WHERE user_id =" .
      loggedInUserId() .
      " AND comment_status='unapproved'"
  );
}
//***** END USER SPECIFIC HELPER FUNCTIONS *****//

//===== AUTHENTICATION HELPER FUNCTIONS =====//

function isAdmin()
{
  if (isLoggedIn()) {
    $result = query(
      "SELECT user_role FROM users WHERE user_id = " . $_SESSION["user_id"] . ""
    );
    $row = fetchRecords($result);

    if ($row["user_role"] == "admin") {
      return true;
    } else {
      return false;
    }
  }
  return false;
}

//***** END AUTHENTICATION HELPER FUNCTIONS *****//

function escape($string)
{
  global $connection;
  return mysqli_real_escape_string($connection, trim($string));
}
function usersOnline()
{
  if (isset($_GET["onlineusers"])) {
    global $connection;

    if (!$connection) {
      session_start();

      include "../includes/db.php";
      $session = session_id();
      $time = time();
      $time_out_in_seconds = 05;
      $time_out = $time - $time_out_in_seconds;

      $query = "SELECT * FROM users_online WHERE session = '$session' ";
      $sendQuery = mysqli_query($connection, $query);
      $count = mysqli_num_rows($sendQuery);

      if ($count == null) {
        mysqli_query(
          $connection,
          "INSERT INTO users_online(session, time) VALUES('$session', '$time')"
        );
      } else {
        mysqli_query(
          $connection,
          "UPDATE users_online SET time = '$time' WHERE session = '$session' "
        );
      }

      $usersOnlineQuery = mysqli_query(
        $connection,
        "SELECT * FROM users_online WHERE time > '$time_out' "
      );
      echo $count_user = mysqli_num_rows($usersOnlineQuery);
    }
  } // ONLINEUSERS GET REQUEST
}
usersOnline();

function confirmQuery($result)
{
  global $connection;
  if (!$result) {
    die("QUERY FAILED ." . mysqli_error($connection));
  }
}

function insertCategories()
{
  global $connection;

  if (isset($_POST["submit"])) {
    $cat_title = $_POST["cat_title"];

    if ($cat_title == "" || empty($cat_title)) {
      echo "This field should not be empty.";
    } else {
      $user_id = loggedInUserId();
      $query = "INSERT INTO categories(cat_title, user_id) VALUE('{$cat_title}', {$user_id}) ";
      $create_category_query = mysqli_query($connection, $query);

      if (!$create_category_query) {
        die("QUERY FAILED." . mysqli_error($connection));
      }
    }
  }
}

function findAllCategories()
{
  global $connection;
  $user_id = loggedInUserId();
  if (isAdmin()) {
    $query = "SELECT * FROM categories";
  } else {
    $query = "SELECT * FROM categories WHERE user_id = $user_id";
  }
  $selectAllCategoriesQuery = mysqli_query($connection, $query);

  while ($row = mysqli_fetch_assoc($selectAllCategoriesQuery)) {
    $cat_id = $row["cat_id"];
    $cat_title = $row["cat_title"];
    echo "<tr>";
    echo "<td>{$cat_id}</td>";
    echo "<td>{$cat_title}</td>";
    echo "<td><a class='btn btn-success' href='categories.php?edit={$cat_id}'>Edit</td>";
    echo "<td><a class='btn btn-danger' href='categories.php?delete={$cat_id}'>Delete</td>";

    echo "</tr>";
  }
}

function deleteCategories()
{
  global $connection;

  if (isset($_GET["delete"])) {
    $get_cat_id = $_GET["delete"];
    $query = "DELETE FROM categories WHERE cat_id = {$get_cat_id} ";
    $delete_query = mysqli_query($connection, $query);
    redirect("Location: categories.php");
  }
}

function selectAllQuery($table)
{
  global $connection;
  $query = "SELECT * FROM $table";
  $selectAllPosts = mysqli_query($connection, $query);
  $result = mysqli_num_rows($selectAllPosts);
  confirmQuery($result);

  return $result;
}
// TODO
function selectAllWhereQuery($table, $columnName, $value)
{
  global $connection;
  $query = "SELECT * FROM $table WHERE $columnName = '$value'";
  $result = mysqli_query($connection, $query);
  confirmQuery($result);

  return mysqli_num_rows($result);
}

function usernameExists($username)
{
  global $connection;

  $query = "SELECT username FROM users WHERE username = '$username' ";
  $result = mysqli_query($connection, $query);

  confirmQuery($result);

  if (mysqli_num_rows($result) > 0) {
    return true;
  } else {
    return false;
  }
}

function emailExists($email)
{
  global $connection;

  $query = "SELECT user_email FROM users WHERE user_email = '$email' ";
  $result = mysqli_query($connection, $query);
  confirmQuery($result);

  if (mysqli_num_rows($result) > 0) {
    return true;
  } else {
    return false;
  }
}

function redirect($location)
{
  header("Location: " . $location);
  exit();
}

function registerUser($firstname, $lastname, $username, $email, $password)
{
  global $connection;
  $firstname = escape($firstname);
  $lastname = escape($lastname);
  $username = escape($username);
  $email = escape($email);
  $password = escape($password);
  $password = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);
  $query =
    "INSERT INTO users (user_firstname, user_lastname, username, user_email, user_password, user_role) ";
  $query .= "VALUES ('{$firstname}', '{$lastname}', '{$username}', '{$email}', '{$password}', 'subscriber' )";
  $registerUserQuery = mysqli_query($connection, $query);
  confirmQuery($registerUserQuery);
}

function loginUser($username, $password)
{
  global $connection;

  $username = escape($username);
  $password = escape($password);
  $query = "SELECT * FROM users WHERE username = '{$username}'";
  $selectUserQuery = mysqli_query($connection, $query);
  if (!$selectUserQuery) {
    die("QUERY FAILED." . mysqli_error($connection));
  }
  while ($row = mysqli_fetch_array($selectUserQuery)) {
    $the_user_id = $row["user_id"];
    $the_username = $row["username"];
    $the_user_password = $row["user_password"];
    $the_user_firstname = $row["user_firstname"];
    $the_user_lastname = $row["user_lastname"];
    $the_user_role = $row["user_role"];

    if (password_verify($password, $the_user_password)) {
      $_SESSION["user_id"] = $the_user_id;
      $_SESSION["username"] = $the_username;
      $_SESSION["firstname"] = $the_user_firstname;
      $_SESSION["lastname"] = $the_user_lastname;
      $_SESSION["user_role"] = $the_user_role;
      if (!headers_sent()) {
        redirect("/cms/");
      } else {
        die(
          "Redirection failed. Go back to <a href='../index.php'>homepage</a>"
        );
      }
    } else {
      return false;
    }
  }
  return true; // $password = crypt($password, $the_user_password);
}

function ifItIsMethod($method = null)
{
  if ($_SERVER["REQUEST_METHOD"] == strtoupper($method)) {
    return true;
  }
  return false;
}

function currentUser()
{
  if (isset($_SESSION["username"])) {
    return isset($_SESSION["username"]);
  }
  return false;
}

function isLoggedIn()
{
  if (isset($_SESSION["user_role"])) {
    return true;
  }
  return false;
}

function loggedInUserId()
{
  if (isLoggedIn()) {
    $result = query(
      "SELECT * FROM users WHERE username='" . $_SESSION["username"] . "'"
    );
    confirmQuery($result);
    $user = mysqli_fetch_array($result);
    return mysqli_num_rows($result) >= 1 ? $user["user_id"] : false;
  }
  return false;
}

function userLiked($post_id)
{
  $result = query(
    "SELECT * FROM likes WHERE user_id=" .
      loggedInUserId() .
      " AND post_id={$post_id}"
  );
  confirmQuery($result);
  return mysqli_num_rows($result) >= 1 ? true : false;
}

function checkIfUserIsLoggedInAndRedirect($redirectLocation = null)
{
  if (isLoggedIn()) {
    redirect($redirectLocation);
  }
}

function getPostLikes($post_id)
{
  $result = query("SELECT * FROM likes WHERE post_id=$post_id");
  confirmQuery($result);

  echo mysqli_num_rows($result);
}
?>
