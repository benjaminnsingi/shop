<?php


namespace App\Data;


use App\Entity\Category;

class Search
{
    /**
     * @var string
     */
    public $string = '';
    /**
     * @var Category[]
     */
    public $categories = [];

    /**
     * @var int
     */
    public $page = 1;
}
