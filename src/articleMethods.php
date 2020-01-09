<?php

$blog = new Blog($database);

if (isset($_GET['failed'])) {
    $args['failed'] = true;
    $args['mode'] = 'new';
    $args['entryTitle'] = $_SESSION['entryTitle'];
    $args['entryContent'] = $_SESSION['entryContent'];
}

if (isset($_GET['new'])) {
    $args['mode'] = 'new';
} elseif (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
    $args['articleContent'] = $blog->fetchPostById($id);

    // I'm not sure if placing this inside the method above would be the best idea
    $args['articleContent']['title'] = htmlspecialchars_decode($args['articleContent']['title'], ENT_QUOTES);
    $args['articleContent']['title'] = strip_tags($args['articleContent']['title']);
    $args['articleContent']['content'] = strip_tags($args['articleContent']['content'], '<p>');

    $args['id'] = $id;
    if ($blog->fetchPostById($id) == false) {
        header('location: /blog.php?notFound');
    }


    if (isset($_GET['edit'])) {
        $args['mode'] = 'edit';
    } elseif (isset($_GET['delete'])) {
        if ($user->getRank() == 3) {
            if ($blog->deleteEntry($id)) {
                header('location: /blog.php?success');
            } else {
                header('location: /blog.php?failed');
            }
        } else {
            header('location: /blog.php?notFound');
        }
    } else {
        $args['mode'] = 'display';
    }
} else {
    header('location: /blog.php?notFound');
}
