<?php
namespace AWC\Controller;

use AWC\Helpers\Func;
use AWC\Helpers\View;
use AWC\Core\Request;
use AWC\Core\CoreController;
use WP_Query;
use AWC\Model\Posts;

class SettingsController extends CoreController{

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * list down page settings
     *
     * @return mixed|string
     * @throws \Exception
     */
    public function index()
    {
        $course = new Posts;

        $courses = $course->where('post_type', 'sfwd_courses');

        return (new View('pages/settings'))
            ->with('courses', $courses)
            ->render();
    }

    public function store(Request $request)
    {

    }

}