<?php
/**
 * k3yDownloads.com
 *
 * @author Rob Lambell <rob@lambell.info>
 * @license MIT
 */

function my_autoloader($class) {
    include 'classes/' . $class . '.class.php';
}
spl_autoload_register('my_autoloader');

define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/');
define('FILE_DIR', realpath('files').DIRECTORY_SEPARATOR);

$excluded_files = array('Thumbs', '.htaccess', '.htpasswd');

if (!empty($_SERVER['QUERY_STRING'])) {

    if (substr(realpath(FILE_DIR . $_SERVER['QUERY_STRING']), 0, strlen(FILE_DIR)) == FILE_DIR) {
        $listing_dir = realpath(FILE_DIR . $_SERVER['QUERY_STRING']);
    } else {
        header('HTTP/1.0 404 Not Found');
        include('404.php');
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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>k3y Downloads</title>
    <link rel="shortcut icon" href="<?=BASE_URL?>favicon.ico" />
    <link rel="stylesheet" type="text/css" href="<?=BASE_URL?>theme/styles.css"/>
</head>
<body>
<div id="container">
    <a href="<?=BASE_URL?>" class="main-logo">k3y Downloads</a>
    <div id="breadcrumbs">
        <a href="<?=BASE_URL?>">Home</a>
    <?php
        $breadcrumbs = array_filter(explode(DIRECTORY_SEPARATOR, substr($listing_dir, strlen(FILE_DIR))));

        foreach ($breadcrumbs as $key => $breadcrumb) {
            $breadcrumb_dir = htmlentities(implode('/', array_slice($breadcrumbs, 0, $key+1)));
            $breadcrumb_name = htmlentities(str_replace('-', ' ', $breadcrumb));
            echo ' <strong>‹</strong> <a href="' . BASE_URL . $breadcrumb_dir . '">' . $breadcrumb_name . '</a>';
        }
        ?></div>
    <div id="listing-container">
        <div id="listing-header">
            <div id="header-filename">Name</div>
            <div id="header-size">Size</div>
            <div id="header-modified">Last Modified</div>
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
                        <img src="<?=BASE_URL?>theme/icons/folder-home.png" alt="Folder"/>
                        <span class="parent_directory_link">Parent Directory</span>
                        <span class="info">
                            <span class="size">&nbsp;</span>&nbsp;
                        </span>
                    </a>
                </div>
                <?php
            }

            foreach ($files as $file) {
                if ($file->isDir()) {
                    $icon = 'folder.png';
                    $filename = htmlentities(str_replace('-', ' ', $file->getFilename()));
                    $size = $file->getHumanSize($file->getDirSize());
                    $modified = date("D M d, Y H:i a", $file->getFileMTime());
                    $dir = substr($file->getPathname(), strlen(FILE_DIR));
                    $url = BASE_URL . htmlentities($dir);
                } else {
                    $icon = $file->getExtension() . '.png';
                    $filename = htmlentities($file->getFilename());
                    $size = $file->getHumanSize();
                    $modified = date("D M d, Y H:i a", $file->getMTime());
                    $url = BASE_URL . htmlentities(implode('/', $breadcrumbs) . '/' . $file->getFilename());
                }
                ?>

                <div>
                    <a href="<?=$url?>">
                        <img src="<?=BASE_URL?>theme/icons/<?=$icon?>" alt="<?=$filename?>"/>
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
<div id="footer">Got teh stuff?
    <a href="http://k3yforums.com/ucp.php?i=pm&amp;mode=compose&amp;u=15943" target="_blank">PM Mystery Man</a> on
    <a href="http://k3yforums.com" target="_blank">k3y Forums</a> for FTP axx.
</div>
</body>
</html>
