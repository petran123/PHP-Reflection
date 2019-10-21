<?php

$blog = new BlogClass;

if (isset($_GET['failed'])) {
    $args['failed'] = true;
    $args['mode'] = 'new';
    $args['entryTitle'] = $_SESSION['entryTitle'];
    $args['entryContent'] = $_SESSION['entryContent'];
} elseif (isset($_GET['new'])) {
    // html purifier commands
    $args['mode'] = 'new';
} elseif (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
    $args['articleContent'] = $blog->fetchPostById($id);

    if ($blog->fetchPostById($id) == false) {
        header('location: /blog.php?notFound');
    }
    $args['mode'] = 'display';
} else {
    header('location: /blog.php?notFound');
}