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

        $option = json_encode(get_option('the-course-content'));

        return (new View('pages/settings'))
            ->with('courses', $courses)
            ->with('option', $option)
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

        $input = $request->input('oc-content-parent-id');

        if($option && !empty($option)) {
            update_option('the-course-content', $request->input('oc-content-parent-id'));
        } else {
            add_option('the-course-content', $input);
        }

        add_option('test-options', 'dawdadawdawedwa');




//        Router::redirect('Plugin Settings');
    }

}
