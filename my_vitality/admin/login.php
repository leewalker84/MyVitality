<!DOCTYPE html>
<html>

<head>
    <title>My Vitality - Dashboard</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

    <?php  include('../view/head_elements_admin_links.inc'); ?>

    <!-- Links to CSS pages -->
    <link href="../styles/main.css" type="text/css" rel="stylesheet" />
    <link href="../styles/grid_layout_twelve.css" type="text/css" rel="stylesheet" />
    <link href="../styles/images.css" type="text/css" rel="stylesheet" />
    <link href="../styles/header.css" type="text/css" rel="stylesheet" />
    <link href="../styles/table.css" type="text/css" rel="stylesheet" />
    <link href="../styles/form.css" type="text/css" rel="stylesheet" />
    <link href="../styles/footer.css" type="text/css" rel="stylesheet" />
    <link href="../styles/button.css" type="text/css" rel="stylesheet" />

</head>

<body>
    <!-- main container for grid -->
    <main id="container">

        <!-- logo  -->
        <?php include('../view/header_login.inc'); ?>

        <div class="main">

        <form class="log_in_form" action=".?action=login" method="post">
            <h1>USER LOGIN</h1>
            <input type="text" class="text-box" name="username" minlength="8" maxlength="30" pattern="^[0-9a-zA-Z]+$" title="Enter only letters or numbers" placeholder="USERNAME" required />
            <input type="password" class="text-box" name="password" minlength="8" maxlength="30" pattern="^[0-9a-zA-Z]+$" title="Enter only letters or numbers" placeholder="PASSWORD" required />
            <input type="submit" id="submit_btn" class="btn-main" name="log-in" value="LOGIN" />
            <p><span class="red-text"><?php echo htmlspecialchars($login_message); ?></span></p>
            <p><a href="#" class="forgot-password-text" data-toggle="modal" data-target="#newPasswordModal">Forgot Password</a></p>
        </form>

        <!-- Modal -->
        <div id="newPasswordModal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-sm">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                  <h3>Enter Username</h3>
                  <form class="new_password_form" action=".?action=new-password" method="post">
                      <input type="text" class="text-box" name="username" minlength="8" maxlength="30" pattern="^[0-9a-zA-Z]+$" title="Enter only letters or numbers" placeholder="USERNAME" required />
                      <input type="submit" id="submit_username_btn" class="btn-main" name="log-in" value="REQUEST" />
                  </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>
        </div>

    <footer id="mainFooter">
        <p class="footer-text">Copyright My Vitality &copy; 2018</p>
    </footer>

    <?php  include('../view/admin_script.inc'); ?>
    </main>


</body>

</html>
