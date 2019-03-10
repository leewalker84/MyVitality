<?php
session_start();
require_once('../util/constants.php');

$errorMessage = "";
$path = "";
if (isset($_SESSION['employee'])) {
    $employeeObject = $_SESSION['employee'];
    $jobID = $employeeObject->getJob();
    $path = HelperFunctions::restrictUserAccess($jobID);
}

if (!empty($_SESSION["database_error_message"])) {
    $errorMessage = $_SESSION['database_error_message']['error'];
}

if (empty($errorMessage)) {
    $errorMessage = ERROR_MSG_DEFAULT;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>My Vitality - Admin Home</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

    <?php  include('../view/head_elements_admin_links.inc'); ?>

    <!-- Links to CSS pages -->
    <link href="../styles/main.css" type="text/css" rel="stylesheet" />
    <link href="../styles/grid_layout_three.css" type="text/css" rel="stylesheet" />
    <link href="../styles/images.css" type="text/css" rel="stylesheet" />
    <link href="../styles/header.css" type="text/css" rel="stylesheet" />
    <link href="../styles/navigation.css" type="text/css" rel="stylesheet" />
    <link href="../styles/footer.css" type="text/css" rel="stylesheet" />
    <link href="../styles/button.css" type="text/css" rel="stylesheet" />

</head>

<body>
    <!-- main container for grid -->
    <main id="container">
        <?php include('../view/header_admin.inc'); ?>

        <?php include("$path"); ?>

        <!-- main content -->
        <article id="mainArticle">
            <h1 class"mainArticleHead">OOOPS, WE FOUND A PROBLEM</h1>
            <p class"mainArticleText"><?php echo htmlspecialchars($errorMessage); ?> </p>
        </article>

        <section id="columnOne" class="info">
            <h4 class="info-head">APPROVE PAYMENTS</h4>
            <figure class="img">
                <a href="index.php?action=orders" class="glyphicon glyphicon-credit-card icon_image"></a>
            </figure>
            <p class="info-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse saepe, id, numquam quod suscipit sit porro! Rerum esse pariatur, ipsum debitis! Delectus neque illo impedit hic quasi ipsa sapiente laudantium repudiandae quae, ipsum aspernatur consectetur architecto saepe sed commodi maiores.</p>
        </section>

        <section id="columnTwo" class="info">
            <h4>CONFIRM SHIPMENT</h4>
            <figure class="img">
                <a href="index.php?action=shipment" class="glyphicon glyphicon-globe icon_image"></a>
            </figure>
            <p class="info-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. onsectetur architecto saepe sed commodi maiores.</p>
        </section>

        <section id="columnThree" class="info">
            <h4>ADD STOCK</h4>
            <figure class="img">
                <a href="index.php?action=stock" class="glyphicon glyphicon-transfer icon_image"></a>
            </figure>
            <p class="info-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse saepe, id, numquam quod suscipit sit porro! Rerum esse pariatur, ipsum debitis! Delectus neque illo impedit hic quasi ipsa sapiente laudantium repudiandae quae, ipsum aspernatur consectetur architecto saepe sed commodi maiores.</p>
        </section>

        <?php include('../view/footer_admin.inc'); ?>

    </main>
    <?php  include('../view/admin_script.inc'); ?>
</body>

</html>
