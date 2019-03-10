<?php

// set the action variable if the user does enter via index.php
$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) { /// if the action variable has not been set, direct to home page as this page is not in the navigation structure availble to users
        header('Location: .?action=home');
    }
}

// get the options for the reference drop down list
try {
    $references = ReferenceDB::getReferenceNameAndID();
} catch (Exception $ex) {
    $error_message = $ex->getMessage();
    require_once('error.php');
    exit();
}

// define variables for form data
$name = $surname = $email = $homePhone = $workPhone = $cellPhone = $reference = $lineOne = $lineTwo = $lineThree = $lineFour = $postCode = "";

// define variables for form error messages
$nameError = $surnameError = $emailError = $homePhoneError = $workPhoneError = $cellPhoneError = $lineOneError = $lineTwoError = $lineThreeError = $lineFourError = $postCodeError = "";


// test whether the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // test whether btn_buy_now has been pressed
    if (isset($_POST['btn_buy_now'])) {

        $valid = TRUE;
        // validate user input

        if (empty($_POST["cus_name"])) { // used as a back up if html5 required attribute fails
            $nameError = ERROR_MSG_REQUIRED;
            $valid = FALSE;
        } else {
            $name = FormatFunctions::isValidInput($_POST["cus_name"]);
            $name = FormatFunctions::formatText($name);
            // validate the name entered - only allow letters and white space
            if (!FormatFunctions::isValidName($name)) {
                $nameError = ERROR_MSG_NAME;
                $valid = FALSE;
            }
        }

        if (empty($_POST["cus_surname"])) { // used as a back up if html5 required attribute fails
            $surnameError = ERROR_MSG_REQUIRED;
            $valid = FALSE;
        } else {
            $surname = FormatFunctions::isValidInput($_POST["cus_surname"]);
            $surname = FormatFunctions::formatText($surname);
            // validate the name entered - only allow letters and white space
            if (!FormatFunctions::isValidName($surname)) {
                $surnameError = ERROR_MSG_NAME;
                $valid = FALSE;
            }
        }

        if (empty($_POST["cus_email"])) { // used as a back up if html5 required attribute fails
            $emailError = ERROR_MSG_REQUIRED;
            $valid = FALSE;
        } else {
            $email = FormatFunctions::isValidInput($_POST["cus_email"]);
            // validate the email Address
            if (!FormatFunctions::isValidEmail($email)) {
                $emailError = ERROR_MSG_EMAIL;
                $valid = FALSE;
            }

        }

        if (empty($_POST["cus_home_phone"])) {
            $homePhone = "0000000000";
        } else {
            $homePhone = FormatFunctions::isValidInput($_POST["cus_home_phone"]);
            // validate the phone number entered
            if (!FormatFunctions::isValidPhone($homePhone)) {
                $homePhoneError = ERROR_MSG_PHONE;
                $valid = FALSE;
            }
        }

        if (empty($_POST["cus_work_phone"])) {
            $workPhone = "0000000000";
        } else {
            $workPhone = FormatFunctions::isValidInput($_POST["cus_work_phone"]);
            // validate the phone number entered
            if (!FormatFunctions::isValidPhone($workPhone)) {
                $workPhoneError = ERROR_MSG_PHONE;
                $valid = FALSE;
            }
        }

        if (empty($_POST["cus_cell_phone"])) {
            $cellPhone = "0000000000";
        } else {
            $cellPhone = FormatFunctions::isValidInput($_POST["cus_cell_phone"]);
            // validate the phone number entered
            if (!FormatFunctions::isValidPhone($cellPhone)) {
                $cellPhoneError = ERROR_MSG_PHONE;
                $valid = FALSE;
            }
        }

        if (empty($_POST["reference"])) {
            $reference = "";
        } else {
            $reference = filter_input(INPUT_POST, 'reference');
            $reference = FormatFunctions::isValidInput($_POST["reference"]);
        }

        if (empty($_POST["line_one"])) { // used as a back up if html5 required attribute fails
            $lineOneError = ERROR_MSG_REQUIRED;
            $valid = FALSE;
        } else {
            $lineOne = FormatFunctions::isValidInput($_POST["line_one"]);
            $lineOne = strtoupper($lineOne);
            if (!FormatFunctions::isValidAddress($lineOne)) {
                $lineOneError = ERROR_MSG_ADDRESS;
                $valid = FALSE;
            }
        }

        if (empty($_POST["line_two"])) {
            $lineTwoError = "";
            $lineTwo = 'NONE';
        } else {
            $lineTwo = FormatFunctions::isValidInput($_POST["line_two"]);
            $lineTwo = strtoupper($lineTwo);
            if (!FormatFunctions::isValidAddress($lineTwo)) {
                $lineTwoError = ERROR_MSG_ADDRESS;
                $valid = FALSE;
            }
        }

        if (empty($_POST["line_three"])) {
            $lineThreeError = "";
            $lineThree = 'NONE';
        } else {
            $lineThree = FormatFunctions::isValidInput($_POST["line_three"]);
            $lineThree = strtoupper($lineThree);
            if (!FormatFunctions::isValidAddress($lineThree)) {
                $lineThreeError = ERROR_MSG_ADDRESS;
                $valid = FALSE;
            }
        }

        if (empty($_POST["line_four"])) {
            $lineFourError = "";
            $lineFour = 'NONE';
        } else {
            $lineFour = FormatFunctions::isValidInput($_POST["line_four"]);
            $lineFour = strtoupper($lineFour);
            if (!FormatFunctions::isValidAddress($lineFour)) {
                $lineFourError = ERROR_MSG_ADDRESS;
                $valid = FALSE;
            }
        }

        if (empty($_POST["postal_code"])) { // used as a back up if html5 required attribute fails
            $postCodeError = ERROR_MSG_REQUIRED;
            $valid = FALSE;
        } else {
            $postCode = FormatFunctions::isValidInput($_POST["postal_code"]);
            if (!FormatFunctions::isValidPostCode($postCode)) {
                $postCodeError = ERROR_MSG_POSTCODE;
                $valid = FALSE;
            }
        }

        if (isset($cartObj)) {
            $valueOfItems = $cartObj->getValueOfItems();
            $numOfItems = $cartObj->getNumOfItems();
        }

        if ($valid) {
            // store data in session, so that message can be shown on confirm screen
            $_SESSION['checkout']['name'] = $name;
            $_SESSION['checkout']['surname'] = $surname;
            $_SESSION['checkout']['email'] = $email;
            $_SESSION['checkout']['homePhone'] = FormatFunctions::formatPhone($homePhone);
            $_SESSION['checkout']['workPhone'] = FormatFunctions::formatPhone($workPhone);
            $_SESSION['checkout']['cellPhone'] = FormatFunctions::formatPhone($cellPhone);
            $_SESSION['checkout']['reference'] = $reference;
            $_SESSION['checkout']['lineOne'] = $lineOne;
            $_SESSION['checkout']['lineTwo'] = $lineTwo;
            $_SESSION['checkout']['lineThree'] = $lineThree;
            $_SESSION['checkout']['lineFour'] = $lineFour;
            $_SESSION['checkout']['postCode'] = $postCode;

            // redirect to buy_now case for processing on index.php
            header('Location: .?action=buy_now');
        }
    }
} // end - if ($_SERVER["REQUEST_METHOD"] == "POST") {

?>

<!DOCTYPE html>
<html>

<head>
<title>My Vitality - Check Out</title> <!-- defines title in browser, title for page when bookmarked, title for page in search engine results -->

<!-- Describe metadata within an HTML document  -->
<!-- meta data is entered in name/value pairs - if content is empty, enter the values in there -->
<meta charset="UTF-8"> <!-- specifies the character set the webite is written in -->
<meta name="keywords" content="health, healthcare, medicine, chinese, accupunture, diet, herbal, therapy"> <!-- define the keywords for search engines in the content section -->
<meta name="description" content="Checkout and purchase your products"> <!-- define the description of web page -->
<meta name="author" content="Lee Walker">
<!-- the viewport element gives the browser instructions on how to control the pages dimension and scaling -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- favicon links -->
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#FFFFFF">
<meta name="msapplication-TileImage" content="../images/favicon-144x144.png">
<meta name="application-name" content="My Vitality">
<link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png">
<link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png">
<link rel="icon" href="images/favicon.ico" type="../image/x-icon">
<!-- end favicon links -->

<!-- links to be able to use bootstrap icons and buttons -->
<!-- online links -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><!-- Latest compiled and minified CSS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script><!-- jQuery library -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>     <!-- Latest compiled JavaScript -->
<!-- end of links to be able to use bootstrap  -->

<!-- Add link to icon library - will give icons for social media brands -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- first style sheet used to create a style baseline to display the CSS the consistently accross browsers -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.min.css" type="text/css" rel="stylesheet"><!-- download the latest version of this sheet from ' https://cdnjs.com/libraries/normalize/ ' choose the one ending in min.css  -->

<link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">

<!-- Links to CSS pages -->
<link href="styles/main.css" type="text/css" rel="stylesheet" />
<link href="styles/grid_layout_seven.css" type="text/css" rel="stylesheet" />
<link href="styles/images.css" type="text/css" rel="stylesheet" />
<link href="styles/header.css" type="text/css" rel="stylesheet" />
<link href="styles/navigation.css" type="text/css" rel="stylesheet" />
<link href="styles/form.css" type="text/css" rel="stylesheet">
<link href="styles/footer.css" type="text/css" rel="stylesheet" />
<link href="styles/button.css" type="text/css" rel="stylesheet" />

</head>

<body>
    <!-- main container for grid -->
    <main id="container">

        <!-- logo, cart -->
        <header id="mainHeader">
            <img id="logo" src="images/logo.png" alt="My Vitality logo" class="resizeable"/>
            <a href="index.php?action=cart" class="cart"><span class="glyphicon glyphicon-shopping-cart"></span>Cart (<?php
                                                                                                                        if (empty($_SESSION['cart'])) {
                                                                                                                            echo '0';
                                                                                                                        } else {
                                                                                                                            echo CartDB::countCartItems();
                                                                                                                        }
                                                                                                                ?> items)</a>
        </header>

        <!-- main site navigation -->
        <nav id="navHeader">
            <ul class="nav">
                <!-- redirect to the controller page for section of site and include action attribute to it can be directed appropriatley
                    "index.php?action= "
                -->
                <li><a href="index.php?action=home">Home</a></li>
                <li><a href="index.php?action=about">About</a></li>
                <li><a href="index.php?action=services">Services</a>
                    <ul>
                        <li class="no-wrap"><a href="index.php?action=herbal">Herbal Therapy</a></li>
                        <li class="no-wrap"><a href="index.php?action=acupuncture">Acupuncture</a></li>
                        <li class="no-wrap"><a href="index.php?action=dietary">Dietary Therapy</a></li>
                    </ul>
                </li>
                <li><a href="index.php?action=supplements">Store</a>
                    <ul>
                        <?php
                        if (isset($suppliersNamesID)) {
                            foreach ($suppliersNamesID as $s):
                                // urlencode()  escape all non-alphanumeric characters in url except - http://php.net/manual/en/function.urlencode.php
                                ?>
                                <li class="no-wrap"><a href="index.php?action=supplier&id=<?php echo urlencode($s->getID()); ?>"><?php echo FormatFunctions::formatText($s->getName()); ?></a></li>
                            <?php endforeach;
                        }
                            ?>
                    </ul>
                </li>
                <li><a href="index.php?action=contact">Contact</a></li>
            </ul>
        </nav>

        <div id="main">
            <h1 id="mainHeading">CHECKOUT</h1>
            <section id="columnOne">
                <h3 class="center">PERSONAL DETAILS</h3>
                <form id="cus_detail" name='cus_detail_form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?action=checkout_cart" method="post">
                    <!-- form spans column one and two -->
                    <section class="cus_form">

                        <label for="cus_name">Name</label>
                        <input type="text" name="cus_name" placeholder="First name" pattern="^[a-zA-Z ']*$" title="Enter only uppercase or lowercase letters, spaces or single quotation marks (')" required value="<?php echo $name; ?>">
                        <span class="error"><?php echo $nameError; ?></span>

                        <label for="cus_surname">Surname</label>
                        <input type="text" name="cus_surname" placeholder="Surname" pattern="^[a-zA-Z ']*$" title="Enter only uppercase or lowercase letters, spaces or single quotation marks (')" required value="<?php echo $surname; ?>">
                        <span class="error"><?php echo $surnameError; ?></span>

                        <label for="cus_email">Email</label>
                        <input type="email" name="cus_email" placeholder="email"  required value="<?php echo $email; ?>">
                        <span class="error"><?php echo $emailError; ?></span>

                        <label for="cus_home_phone">Home Phone</label>

                        <input type="text" name="cus_home_phone" placeholder="10 digit phone number" pattern="^\(\d{3}\)\-\(\d{3}\)-\(\d{4}\)$" title="use following format (###)-(###)-(####) where # reperesting a valid digit 0-9" value="<?php if ($homePhone !== '0000000000') {
                                                                                                                                                                                                                                            echo htmlspecialchars($homePhone); } ?>">

                        <span class="error"><?php echo $homePhoneError; ?></span>

                        <label for="cus_work_phone">Work Phone</label>
                        <input type="text" name="cus_work_phone" placeholder="10 digit phone number" pattern="^\(\d{3}\)\-\(\d{3}\)-\(\d{4}\)$" title="use following format (###)-(###)-(####) where # reperesting a valid digit 0-9" value="<?php if ($workPhone !== '0000000000') {
                                                                                                                                                                                                                                            echo htmlspecialchars($workPhone); } ?>">
                        <span class="error"><?php echo $workPhoneError; ?></span>

                        <label for="cus_cell_phone">Cell Phone</label>
                        <input type="text" name="cus_cell_phone" placeholder="10 digit phone number" pattern="^\(\d{3}\)\-\(\d{3}\)-\(\d{4}\)$" title="use following format (###)-(###)-(####) where # reperesting a valid digit 0-9" value="<?php if ($cellPhone !== '0000000000') {
                                                                                                                                                                                                                                            echo htmlspecialchars($cellPhone); } ?>">
                        <span class="error"><?php echo $cellPhoneError ?></span>

                        <label for="cus_refer">Who referred you to us</label>
                        <select name="reference">
                            <?php

                            foreach ($references as $reference) :
                                $refID = $reference->getID();
                                $refName = $reference->getName();
                                ?>
                            <option value="<?php echo htmlspecialchars($refID); ?>"><?php echo htmlspecialchars($refName); ?></option>
                            <?php
                        endforeach;

                        ?>
                    </select>
                </section>
                <!--</form>-->

            </section>

            <section id="columnTwo">
                <h3 class="center">ADDRESS</h3>
                <section class="cus_form">
                    <label for="line_one">Line One</label>
                    <input type="text" name="line_one" placeholder="House number and street" required value="<?php echo htmlspecialchars($lineOne); ?>">
                    <span class="error"><?php echo htmlspecialchars($lineOneError); ?></span>

                    <label for="line_two">Line Two</label>
                    <input type="text" name="line_two" placeholder="Town" value="<?php echo htmlspecialchars($lineTwo); ?>">
                    <span class="error"><?php echo htmlspecialchars($lineTwoError); ?></span>

                    <label for="line_three">Line Three</label>
                    <input type="text" name="line_three" placeholder="City" value="<?php echo htmlspecialchars($lineThree); ?>">
                    <span class="error"><?php echo htmlspecialchars($lineThreeError); ?></span>

                    <label for="line_four">Line Four</label>
                    <input type="text" name="line_four" placeholder="Province" value="<?php echo htmlspecialchars($lineFour); ?>">
                    <span class="error"><?php echo htmlspecialchars($lineFourError); ?></span>

                    <label for="postal_code">Postal Code</label>
                    <input type="text" name="postal_code" pattern="[0-9]{4}" title="Enter a four digit postal code" placeholder="0000 for outside SA" required value="<?php echo htmlspecialchars($postCode); ?>">
                    <span class="error"><?php echo htmlspecialchars($postCodeError); ?></span>
                </section>
            </form>
        </section>

        <section id="columnThree">
            <h3 class="extra_padding_bottom center">ORDER SUMMARY</h3>

            <p><span class="bold">Total R<?php echo htmlspecialchars($valueOfItems); ?></span><br />(<?php echo htmlspecialchars($numOfItems); ?> Items)</p>
            <p>All items are in stock</p>
            <input type="submit" class="btn-main extra_padding_bottom" name="btn_buy_now" value="BUY NOW" form="cus_detail">
            <p>Payment via EFT</p>

        </section>

    </div>

    <?php include('view/footer_top.inc'); ?>

    <footer id="mainFooter">
        <p class="footer-text">Copyright My Vitality &copy; 2018</p>
    </footer>

</main>
</body>

</html>
