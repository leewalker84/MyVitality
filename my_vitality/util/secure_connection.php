<?php
// force login page to use a secure connection
/*
HTTPS - returns non empty value if current request is using HTTPS
HTTP-HOST -returns the host for current request
REQUEST_URI - returns the uniform resource identifier for the current request (the current URL)
*/

if (!isset($_SERVER['HTTPS'])) {
    $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: " . $url);
    exit();
}

?>
