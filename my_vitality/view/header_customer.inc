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
