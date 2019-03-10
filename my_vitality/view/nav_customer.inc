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
