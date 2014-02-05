<?php

function my_autoloader($class) {
    require_once 'classes/' . $class . '.class.php';
}
spl_autoload_register('my_autoloader');

define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/');
define('FILE_DIR', realpath('files').DIRECTORY_SEPARATOR);

$excluded_files = array('.gitignore', '.htaccess', '.htpasswd', '.ftpquota');

if (!empty($_SERVER['QUERY_STRING'])) {

    if (substr(realpath(FILE_DIR . $_SERVER['QUERY_STRING']), 0, strlen(FILE_DIR)) == FILE_DIR) {
        $listing_dir = realpath(FILE_DIR . $_SERVER['QUERY_STRING']);
    } else {
        header('HTTP/1.0 404 Not Found');
        require_once '404.php';
        exit;
    }

    if (is_file($listing_dir)) {
        $fileDownload = FileDownload::createFromFilePath($listing_dir);
        $fileDownload->sendDownload(pathinfo($listing_dir, PATHINFO_BASENAME), false);
        exit;
    }

} else {
    $listing_dir = FILE_DIR;
}

$files = new SortableIterator(new FilteredFilesystemIterator($listing_dir, $excluded_files), 'strnatcasecmp');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=620" />
    <title>k3y Downloads</title>
    <meta name="description" content="Downloads for xk3y and 3k3y. Firmware Updates, Micro SD Images, User Manuals, Apps &amp; Other Files." />
    <link rel="shortcut icon" href="<?=BASE_URL?>favicon.ico" />
    <link rel="stylesheet" type="text/css" href="<?=BASE_URL?>theme/styles.css"/>
</head>
<body>
<div id="container">
    <a href="<?=BASE_URL?>" class="main-logo">k3y Downloads</a>
    <div id="breadcrumbs">
        <a href="<?=BASE_URL?>">Home</a>
    <?php
        $breadcrumbs = explode(DIRECTORY_SEPARATOR, substr($listing_dir, strlen(FILE_DIR)));
        $breadcrumbs = array_filter($breadcrumbs);

        foreach ($breadcrumbs as $key => $breadcrumb) {
            $breadcrumb_dir = htmlentities(implode('/', array_slice($breadcrumbs, 0, $key+1)));
            $breadcrumb_name = htmlentities(str_replace('-', ' ', $breadcrumb));
            echo ' <strong>‹</strong> <a href="' . BASE_URL . $breadcrumb_dir . '">' . $breadcrumb_name . '</a>';
        }
        ?></div>
    <div id="listing-container">
        <div id="listing-header">
            <span class="filename">Name</span>
            <span class="size">Size</span>
            <span class="modified">Last Modified</span>
        </div>
        <div id="listing">
            <?php
            if (count($breadcrumbs) > 0) {
                if (count($breadcrumbs) == 1) {
                    $url = BASE_URL;
                } else {
                    $parent_dir = implode('/', array_slice($breadcrumbs, 0, count($breadcrumbs) - 1));
                    $url = BASE_URL . htmlentities($parent_dir);
                }
                ?>

                <div><a href="<?=$url?>">
                        <img src="<?=BASE_URL?>theme/icons/folder-home.png" class="icon" alt="Folder"/>
                        <span class="parent_directory_link">Parent Directory</span>
                        <span class="info">
                            <span class="size">&nbsp;</span>
                            <span class="modified">&nbsp;</span>
                        </span>
                    </a>
                </div>
                <?php
            }

            foreach ($files as $file) {
                if ($file->isDir()) {
                    $icon = 'folder.png';
                    $filename = htmlentities(str_replace('-', ' ', $file->getFilename()));
                    $dir = substr($file->getPathname(), strlen(FILE_DIR));
                    $url = BASE_URL . htmlentities($dir);
                } else {
                    $icon = $file->getExtension() . '.png';
                    $filename = htmlentities($file->getFilename());
                    $path = implode('/', $breadcrumbs) . '/' . $file->getFilename();
                    $url = BASE_URL . htmlentities($path);
                }
                $size = $file->getHumanSize();
                $modified = date("D M d, Y g:i a", $file->getFileMTime());
                ?>

                <div>
                    <a href="<?=$url?>">
                        <img src="<?=BASE_URL?>theme/icons/<?=$icon?>" class="icon" alt="<?=$filename?>"/>
                        <span class="filename"><?=$filename?></span>
                        <span class="info">
                            <span class="size"><?=$size?></span>
                            <span class="modified"><?=$modified?></span>
                        </span>
                    </a>
                </div>
            <?php
            }
            ?>

        </div>
    </div>
</div>
<div id="footer">
    <a href="http://k3yforums.com" target="_blank">k3y Forums</a> &middot;
    <a href="https://github.com/badgio/k3ydownloads.com" target="_blank">GitHub</a>
</div>
</body>
</html>
