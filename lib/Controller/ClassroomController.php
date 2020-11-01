<?php
namespace AWC\Controller;

use AWC\Helpers\Func;
use AWC\Helpers\View;
use AWC\Core\Request;
use AWC\Core\CoreController;
use AWC\Model\AccessDevice;
use Carbon\Carbon;
use WP_Query;
use AWC\Model\Posts;


class ClassroomController extends CoreController{

    public function __construct()
    {
        parent::__construct();
    }

    public function view(Posts $id)
    {

    }


    /**
     *
     *
     * @param Request $request
     * @throws \Exception
     */
    public function store(Request $request)
    {

        $lessonRequests = $request->input('lesson-name' );

        //Get Dolly
        $dolly = new Posts;
        $dolly->find($request->input('course-content'));

        //Cloning process for the course
        $course = new Posts;
        $course->post_title = $request->input('course-title');
        $course->post_author = get_current_user_id();
        $course->post_content = $dolly->post_content;
        $course->post_excerpt = $dolly->post_excerpt;
        $course->post_status = 'publish';
        $course->post_type = $dolly->post_type;

        var_dump($request->all());

        //Save to database
//        if( wp_insert_post($course->get_columns() ) ){

            //Once the main course is saved, process the lesson

        foreach($lessonRequests as $lessonRequest) {
            $lesson = new Posts;

            $lesson->post_title = $lessonRequest;
            $lesson->post_author = get_current_user_id();


        }



//        }

    }


}
