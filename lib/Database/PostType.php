<?php
/**
 * Created by PhpStorm.
 * User: Shore360
 * Date: 27/09/2018
 * Time: 3:48 PM
 */

namespace AWC\Database;

class PostType
{

    protected $name;

    protected $args = [];

    protected $labels;

    public function __construct( $name, array $args, array $labels)
    {
        $this->name = $name;
        $this->args = $args;
        $this->labels = $labels;
    }

    public function register()
    {

    }

    public function addTaxonomy(){

    }

    public function save()
    {

    }




}
