<?php

function setActiveCategory($category, $output = 'font-weight-bold'){
    return request()->category == $category ? $output : '';
}

function productImage($path){
    return $path && file_exists('storage/'.$path) ? asset('storage/'.$path) : asset('img/not-found.jpg');
}