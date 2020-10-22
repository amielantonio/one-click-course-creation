<?php
namespace AWC\Controller;

use AWC\Helpers\Func;
use AWC\Helpers\View;
use AWC\Core\Request;
use AWC\Core\CoreController;
use Router;
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

        $courses = $course->select(['ID, post_title'])->where('post_type', 'sfwd-courses')->results();

        return (new View('pages/settings'))
            ->with('courses', $courses)
            ->render();
    }

    /**
     * Requests
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $option = get_option('one-click-course-content');

        if($option && !empty($option)) {
            update_option('one-click-course-content', $request->input('oc-content-parent-id'));
        } else {
            add_option('one-click-course-content', $request->input('oc-content-parent-id'));
        }


        $input = $request->input('oc-content-parent-id');


        Router::redirect('Plugin Settings');
    }

}
