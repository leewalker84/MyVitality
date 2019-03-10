<?php
require_once('../util/valid_user.php'); // test if user is a valid administrative user

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
            <h1 class"mainArticleHead">WELCOME TO WORK <?php echo htmlspecialchars($name); ?>  <?php echo htmlspecialchars($surname); ?></h1>
            <p class"mainArticleText">
                <?php
                    if (!empty($task)) {
                        echo htmlspecialchars($task) . '<br><br>';
                    }

                    if (!empty($stock)) {
                        echo htmlspecialchars($stock);
                    }

                ?>
            </p>
        </article>

        <section id="columnOne" class="info">
            <h4 class="info-head">APPROVE PAYMENTS</h4>
            <figure class="img">
                <?php if ($jobID == 1 || $jobID == 3) { ?>
                    <a href="index.php?action=orders" class="glyphicon glyphicon-credit-card icon_image"></a>
                <?php } else { ?>
                    <span class="glyphicon glyphicon-credit-card icon_image_no_link"></span>
                <?php } ?>
            </figure>
            <p class="info-text">
                <?php if ($jobID == 1 || $jobID == 3) { ?>
                    Enter the transaction system
                <?php } else { ?>
                    You do not have access to this section of the site
                <?php } ?>
            </p>
        </section>

        <section id="columnTwo" class="info">
            <h4>CONFIRM SHIPMENT</h4>
            <figure class="img">
                <?php if ($jobID == 2 || $jobID == 3) { ?>
                    <a href="index.php?action=shipment" class="glyphicon glyphicon-globe icon_image"></a> 
                <?php } else { ?>
                    <span class="glyphicon glyphicon-globe icon_image_no_link"></span>
                <?php } ?>
            </figure>
            <p class="info-text">
                <?php if ($jobID == 2 || $jobID == 3) { ?>
                    Enter the order fullfillment system
                <?php } else { ?>
                    You do not have access to this section of the site
                <?php } ?>
            </p>
        </section>

        <section id="columnThree" class="info">
            <h4>ADD STOCK</h4>
            <figure class="img">
                <a href="index.php?action=stock" class="glyphicon glyphicon-transfer icon_image"></a>
            </figure>
            <p class="info-text">Enter the inventory system</p>
        </section>

        <?php include('../view/footer_admin.inc'); ?>

    </main>
    <?php  include('../view/admin_script.inc'); ?>
</body>

</html>
