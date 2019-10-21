<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/blogMethods.php';

/*
so, what do we want for a blog?
-a database with blog titles, entries, dates
-a sql query to grab up to 10 entries, newest first / limiting at the start creates a problem with pagination. I have split it on twig instead
-display a list of titles with up to x characters per
click on title to open the entire blog post
add pages/archive by month
add next/previous links to each entry
admins should have a form to create a new entry.
you should be able to place html tags in blog posts BUT
how would we keep it safe? strip scripts only? I'm not sure that would be enough
*/

//id is 1-indexed.
//add an input field for a page jump

$args['l2'] = "selected";
echo $twig->render('blog.html', $args);