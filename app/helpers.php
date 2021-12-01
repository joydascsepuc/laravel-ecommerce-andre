<?php

function setActiveCategory($category, $output = 'font-weight-bold'){
    return request()->category == $category ? $output : '';
}
