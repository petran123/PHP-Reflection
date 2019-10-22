<?php

$blog = new BlogClass;

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

    $args['id'] = $id;
    if ($blog->fetchPostById($id) == false) {
        header('location: /blog.php?notFound');
    }

    $args['articleContent']['title'] = htmlspecialchars_decode($args['articleContent']['title'], ENT_QUOTES);
    $args['articleContent']['title'] = strip_tags($args['articleContent']['title']);
    $args['articleContent']['content'] = strip_tags($args['articleContent']['content'], '<p>');

    if (isset($_GET['edit'])) {
        $args['mode'] = 'edit';        
    } elseif (isset($_GET['delete'])) {
        if ($acc->getRank() == 3) {
            if ($blog->delete($id)) {
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