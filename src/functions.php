<?php

function request() 
{
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
} 