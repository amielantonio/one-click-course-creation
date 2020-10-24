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

        $getOptions = get_option('the-course-content');
        $option = [];
        if(!empty($getOptions)) {

            foreach($getOptions as $getOption) {
                $courseSelected = get_post($getOption);
                $option[$courseSelected->ID] = $courseSelected->post_title;
            }
        }

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
        $option = get_option('the-course-content');

        $input = $request->input('oc-content-parent-id');

        if( count($option) > 0 ) {
            update_option('the-course-content', $input);
        } else {
            add_option('the-course-content', $input);
        }

//        Router::redirect('Plugin Settings');
    }

}
