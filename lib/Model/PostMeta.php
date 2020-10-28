<?php
namespace AWC\Model;

use AWC\Core\CoreModel;

class PostMeta extends CoreModel {

    protected $table = "postmeta";

    protected $primary_key = "meta_id";

    public function __construct()
    {
        parent::__construct();
    }

}
