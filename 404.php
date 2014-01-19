<?php

if(!defined('BASE_URL')){
    define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>k3y Downloads</title>
    <link rel="shortcut icon" href="<?=BASE_URL?>favicon.ico" />
    <link rel="stylesheet" type="text/css" href="<?=BASE_URL?>theme/styles.css"/>
</head>
<body>
<div id="container">
    <a href="<?=BASE_URL?>" class="main-logo">k3y Downloads</a>
    <div id="breadcrumbs">
        <a href="<?=BASE_URL?>">Home</a> <strong>‹</strong> Error 404 Page Not Found
    </div>
    <div id="listing-container">
        <div id="listing-header">
            <div id="header-filename">Name</div>
            <div id="header-size">Size</div>
            <div id="header-modified">Last Modified</div>
        </div>
        <div id="listing">

            <div><a href="<?=BASE_URL?>">
                    <img src="<?=BASE_URL?>theme/icons/unknown.png" alt="404"/>
                    <span class="parent_directory_link">Return to Home</span>
                        <span class="info">
                            <span class="size">&nbsp;</span>&nbsp;
                        </span>
                </a>
            </div>

        </div>
    </div>
</div>
<div id="footer">Got teh stuff?
    <a href="http://k3yforums.com/ucp.php?i=pm&amp;mode=compose&amp;u=15943" target="_blank">PM Mystery Man</a> on
    <a href="http://k3yforums.com" target="_blank">k3y Forums</a> for FTP axx.
</div>
</body>
</html>
