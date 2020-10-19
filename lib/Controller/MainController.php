<?php
namespace AWC\Controller;

use AWC\Helpers\Func;
use AWC\Helpers\View;
use AWC\Core\Request;
use AWC\Core\CoreController;
use AWC\Model\AccessDevice;
use WP_Query;


class MainController extends CoreController{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Landing Page
     *
     * @return mixed
     * @throws \exception
     */
    public function index()
    {


        return (new View('steps/steps'))->render();
    }

    public function update(){

        $args = [
                    'p' => 88724, // GET the ID of the edit post
                    'post_type' => 'sfwd-courses',   
                ];
        $my_query = new WP_Query($args);
       
      

        return (new View('pages/update'))->with('data',$my_query)->render();
    }

}
