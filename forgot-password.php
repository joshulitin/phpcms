
<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php include "includes/navigation.php"; ?>

<?php
require "./vendor/autoload.php";
if (!isset($_GET["forgot"])) {
  redirect("index");
}
if (ifItIsMethod("post")) {
  if (isset($_POST["email"])) {
    $email = $_POST["email"];
    $length = 50;
    $token = bin2hex(openssl_random_pseudo_bytes($length));
    if (emailExists($email)) {
      if (
        $stmt = mysqli_prepare(
          $connection,
          "UPDATE users SET token='{$token}' WHERE user_email = ?"
        )
      ) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        // Configure PHPMAILER
        $mail = new PHPMailer();
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = Config::SMTP_HOST; //Set the SMTP server to send through
        $mail->SMTPAuth = true;
        //Enable SMTP authentication
        $mail->Username = Config::SMTP_USER; //SMTP username
        $mail->Password = Config::SMTP_PASSWORD; //SMTP password
        $mail->SMTPSecure = "tls";
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";
        $mail->setFrom("xirinnn@gmail.com", "Joshua Ulitin");
        $mail->addAddress($email);
        $mail->Subject = "This is a test email"; //Enable implicit TLS encryption
        $mail->Port = Config::SMTP_PORT;
        $mail->Body =
          "<p>Please click here to reset your password: <a href='http://localhost/cms/reset.php?email=" .
          $email .
          "&token=" .
          $token .
          "'>http://localhost/cms/reset.php?email=" .
          $email .
          "&token=" .
          $token .
          "</a></p>";
        if ($mail->send()) {
          $emailSent = true;
        } else {
          echo "Not Sent.";
        }
      } else {
        echo "mysqli_error($connection)";
      }
    }
  }
}
?>
<!-- Page Content -->
<div class="container">

    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">

                        <?php if (!isset($emailSent)): ?>
                                <h3><i class="fa fa-lock fa-4x"></i></h3>
                                <h2 class="text-center">Forgot Password?</h2>
                                <p>You can reset your password here.</p>
                                <div class="panel-body">

                                    <form id="register-form" role="form" autocomplete="off" class="form" method="post">

                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                                <input id="email" name="email" placeholder="email address" class="form-control"  type="email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                                        </div>

                                        <input type="hidden" class="hide" name="token" id="token" value="">
                                    </form>

                                </div><!-- Body-->
                                <?php else: ?>
                                  <h3>Please check your email</h3>
                                <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <hr>

    <?php include "includes/footer.php"; ?>

</div> <!-- /.container -->

