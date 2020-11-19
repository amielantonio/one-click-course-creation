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

    use LessonMeta, CourseMeta;


    public function __construct()
    {
        parent::__construct();
    }

    public function view(Posts $posts)
    {
        var_dump($posts);
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

        $dates = $request->input('topic-date');
        $lessonNames = $request->input('lesson-name');
        $lessonIds = $request->input('lesson-id');


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
            for ($i = 0; $i < count($lessonNames); $i++) {
                // Save the lessons to swfd-lesson post type
                $lesson = new Posts;
                $dollyLesson = new Posts;

                $dollyLesson->find($lessonIds[$i]);

                //Populate Lesson Model with the info needed to insert the lesson in WP_Post
                $lesson->post_title = $lessonNames[$i];

                $lesson->post_author = get_current_user_id();
                $lesson->post_content = $dollyLesson->post_content;
                $lesson->post_excerpt = $dollyLesson->post_excerpt;
                $lesson->post_status = "publish";
                $lesson->post_type = $dollyLesson->post_type;

                //Save Lesson to database
                if ($arrLessons[] = $lesson_id = wp_insert_post($lesson->get_columns())) {
                    //Post meta

                    //Create ld_course_steps meta
                    $this->save_lesson_meta($lesson_id, $course_id, $request, $dollyLesson);

                    //Check if there is a date available for the current array node.
                    $lessonDate = $dates[$i] <> "" ? Carbon::createFromFormat('d F, Y g:i a', $dates[$i])->format('Y-m-d g:i a') : "";

                    //Add new leson meta
                    $new_lesson_meta = [
                        "sfwd-lessons_visible_after_specific_date" => $lessonDate
                    ];

                    add_post_meta($lesson_id, '_sfwd-lessons', $this->create_sfwd_lesson($lesson_id, $dollyLesson, $new_lesson_meta));

                    echo "{$lessonNames[$i]} lessons created<br />";

                    add_post_meta($lesson_id, 'ld_course_steps', $this->create_ld_course_steps($arrLessons));
                } else {
                    echo "{$lessonNames[$i]} was not created<br />";
                }
            }

            //Create Course Steps post meta
            add_post_meta($course_id, 'ld_course_steps', $this->create_ld_course_steps($arrLessons));

            //Save the relationship as post meta
            add_post_meta($course_id, 'created-from-one-click', true);

        } else {
            echo "what just happened?";
        }

//        _redirect('https://coursesstaging3.writerscentre.com.au/wp-admin/admin.php?page=one-click-classroom-setup', []);

    }
}