<?php
namespace AWC\Model;

use AWC\Core\CoreModel;

class AccessDevice extends CoreModel {

    protected $table = "strataplan_aquisitions";

    protected $primary_key = "id";

    public function __construct()
    {
        parent::__construct();
    }

}
