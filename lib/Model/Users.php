<?php
namespace AWC\Model;

use AWC\Core\CoreModel;

class Users extends CoreModel {

    protected $table = "users";

    protected $primary_key = "ID";

    public function __construct()
    {
        parent::__construct();
    }

}
