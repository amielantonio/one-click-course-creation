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

        $excludeKeywords = implode(',', get_option('exclude-module-keywords'));

        $option = [];
        if(!empty($getOptions)) {

            foreach($getOptions as $getOption) {
                $courseSelected = get_post($getOption);
                $option[$courseSelected->ID] = $courseSelected->post_title;
            }
        }

        return (new View('pages/settings'))
            ->with('courses', $courses)
            ->with('excludeKeywords', $excludeKeywords)
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
        $options = [
            'the-course-content' => [
                'get' => get_option('the-course-content'),
                'val' => $request->input('oc-content-parent-id')
            ],
            'exclude-module-keywords' => [
                'get' => get_option('exclude-module-keywords'),
                'val' => explode(',',$request->input('oc-exclude-module-keywords') )
            ]
        ];

        foreach($options as $key => $option) {

            if( count($option['get']) > 0 ) {
                update_option($key, $option['val']);
            } else {
                add_option($key, $option['val']);
            }

        }

        header('Location: https://coursesstaging3.writerscentre.com.au/wp-admin/admin.php?page=course-setup');

//        Router::redirect('Plugin Settings');
    }

}
