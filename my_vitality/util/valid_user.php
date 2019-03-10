<?php 
// check that the user is a valid administrative user, else redirect to login page
if (!isset($_SESSION['valid_admin'])) {
    header('Location: ../admin/.');
}

// call this file at the top of all admin pages, except index and login

?>
