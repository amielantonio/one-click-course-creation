<?php

namespace AWC\Controller;

use AWC\Helpers\Func;
use AWC\Helpers\View;
use AWC\Core\Request;
use AWC\Core\CoreController;
use AWC\Model\AccessDevice;
use AWC\Traits\CourseMeta;
use AWC\Traits\LessonMeta;
use Carbon\Carbon;
use WP_Query;
use AWC\Model\Posts;


class ClassroomController extends CoreController
{

    use CourseMeta, LessonMeta;

    public function __construct()
    {
        parent::__construct();
    }

    public function view(Posts $posts)
    {



        return (new View('steps/steps'))->render();
    }


    /**
     *
     *
     * @param Request $request
     * @throws \Exception
     */
    public function store(Request $request)
    {


        $arrLessons = [];

        $lessonName = $request->input('lesson-name');
        $lessonID = $request->input('lesson-id');


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

        //Save to database

        if ($course_id = wp_insert_post($course->get_columns())) {
            echo "{$request->input('course-title')} course created<br />";

            // Post meta for the course
            $this->save_course_meta($course_id, $request, $dolly);

            // Once the main course is saved, process the lesson
            for ($i = 0; $i < count($lessonName); $i++) {
                // Save the lessons to swfd-lesson post type
                $lesson = new Posts;
                $dollyLesson = new Posts;
                $dollyLesson->find($lessonID[$i]);

                $lesson->post_title = $lessonName[$i];
                $lesson->post_author = get_current_user_id();
                $lesson->post_content = $dollyLesson->post_content;
                $lesson->post_excerpt = $dollyLesson->post_excerpt;
                $lesson->post_status = "publish";
                $lesson->post_type = $dollyLesson->post_type;

                //Save Lesson to database
                if ( $arrLessons[] = $lesson_id = wp_insert_post($lesson->get_columns()) ) {
                    //Post meta

                    //Create ld_course_steps meta
                    $this->save_lesson_meta($lesson_id, $course_id, $request, $dollyLesson);

                    echo "{$lessonName[$i]} lessons created<br />";

                    add_post_meta($lesson_id, 'ld_course_steps', $this->create_ld_course_steps($arrLessons));
                } else {
                    echo "{$lessonName[$i]} was not created";
                }
            }

            //Create Course Steps post meta
            add_post_meta($course_id, 'ld_course_steps', $this->create_ld_course_steps($arrLessons));

            //Save the relationship as post meta
            add_post_meta($course_id, 'created-from-one-click', true);

        }

        //_redirect('https://coursesstaging3.writerscentre.com.au/wp-admin/admin.php?page=one-click-classroom-setup', []);

    }
}
