<?php

$postsPerPage = 4;

$blog = new BlogClass;
$args['articleList'] = $blog->FetchAllPosts();


foreach ($args['articleList'] as $key => $value) {
    $args['articleList'][$key]['title'] = htmlspecialchars_decode($args['articleList'][$key]['title'], ENT_QUOTES);
    $args['articleList'][$key]['title'] = strip_tags($args['articleList'][$key]['title']);
    $args['articleList'][$key]['content'] = strip_tags($args['articleList'][$key]['content']);
    //title doesn't need to be unescaped in twig so the problem is somewhere else.
}
$args['articleList'][0]['title'] = htmlspecialchars_decode($args['articleList'][0]['title'], ENT_QUOTES);
// var_dump($args['articleList'][0]['title']);



$totalPages = ceil(count($args['articleList']) / $postsPerPage);

// todo: move operations into a class if possible

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
// check if this actually works 
    if ($acc->getRank() < 2) {
        header('location: /article.php?failed');
    }
    
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $dirty_content = $_POST['content'];
    $clean_content = $purifier->purify($dirty_content);
    
    if (isset($_GET['edit'])) {
        if (!empty($_GET['id'])) {
            // this id is a string but it works just the same
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            if ($blog->editEntry($id, $title, $clean_content)) {
                header('location: /blog.php?success');
            } else {
                header("location: /article.php?id=$id&edit=failed");
            }

        } else {
            header('location: /blog.php?notFound');
        }
    } else {
    
        if ($blog->addEntry($title, $clean_content)) {
            $_SESSION['entryTitle'] = "";
            $_SESSION['entryContent'] = "";
            header('location: /blog.php?success');
        } else {
            $_SESSION['entryTitle'] = $title;
            $_SESSION['entryContent'] = $clean_content;
            header('location: /article.php?failed');
        }
    }
}