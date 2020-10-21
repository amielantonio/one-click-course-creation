<?php
namespace AWC\Model;

use AWC\Core\CoreModel;

class Posts extends CoreModel {

    protected $table = "posts";

    protected $primary_key = "ID";

    public function __construct()
    {
        parent::__construct();
    }

}
