<?php

$postsPerPage = 2;

$blog = new BlogClass;
$args['articleList'] = $blog->FetchAllPosts();
$totalPages = ceil(count($args['articleList']) / $postsPerPage);


if (empty($_GET['page'])) {
    $page = 1;
} else  {
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
    if ($page > $totalPages) {
        header('location: /blog.php?notFound');
    }
}

if (isset($_GET['notFound'])) {
    $args['notFound'] = true;
}

if (isset($_GET['success'])) {
    $args['success'] = true;
}

if (isset($_GET['failed'])) {
    $args['failed'] = true;
}

$args['articlePages'] = ['currentPage' => $page, 'totalPages' => $totalPages, 'postsPerPage' => $postsPerPage];

if (!empty($_POST)) {
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $dirty_content = $_POST['content'];
    $clean_content = $purifier->purify($dirty_content);
    if ($blog->addEntry($title, $clean_content)) {
        header('location: /blog.php?success');
    } else {
        $_SESSION['entryTitle'] = $title;
        $_SESSION['entryContent'] = $clean_content;
        header('location: /article.php?failed');
    }
}