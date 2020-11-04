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


class ClassroomController extends CoreController
{

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


//        var_dump(get_post_meta(89111, '_sfwd-courses' )[0]['sfwd-courses_certificate']);
//        var_dump(get_post_meta(89111, '_is4wp_access_tags' ));

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
                }
            }

            //Create Course Steps post meta
            add_post_meta($course_id, 'ld_course_steps', $this->create_ld_course_steps($arrLessons));
        }
//
//        _redirect('https://coursesstaging3.writerscentre.com.au/wp-admin/admin.php?page=one-click-classroom-setup', []);

    }

    /**
     * Save course meta
     *
     * @param $course_id
     * @param $request
     * @param $dolly
     */
    private function save_course_meta($course_id, $request, $dolly)
    {
        $private_commenting = ($request->input('awc_private_comments') <> "") ? "Allow private comments" : "";
        $collapse_replies = ($request->input('collapse_replies_for_course') <> "") ? "Collapse replies for this course" : "";

        add_post_meta($course_id, '_is4wp_access_tags', implode(', ', $request->input('oc-tag-id')));
        add_post_meta($course_id, 'awc_active_course', $request->input('awc_active_course'));
        add_post_meta($course_id, 'email_daily_comment_digest', $request->input('email_daily_comment_digest'));
        add_post_meta($course_id, 'cc_recipients', $request->input('cc_recipients'));
        add_post_meta($course_id, 'awc_private_comments', $private_commenting);
        add_post_meta($course_id, 'collapse_replies_for_course',  $collapse_replies );
        $this->duplicate_course_meta($course_id, $dolly);
    }

    /**
     * Save lesson meta
     *
     * @param $lesson_id
     * @param $course_id
     * @param $request
     * @param $dollyLesson
     */
    public function save_lesson_meta($lesson_id, $course_id, $request, $dollyLesson)
    {

        add_post_meta($lesson_id, 'ld_course_' . $course_id, $course_id);
        $this->duplicate_lesson_meta($lesson_id, $dollyLesson);
    }


    /**
     * Create ld course steps for Learndash relations
     *
     * @param $lesson_ids
     * @return array
     */
    private function create_ld_course_steps($lesson_ids)
    {
        $course_steps = [];
        $h = [];
        $t = [];
        $r = [];
        $l = [];

        //Process Lessons
        foreach ($lesson_ids as $lesson_id) {
            $h['sfwd-lessons'][$lesson_id] = [
                'sfwd-topic' => [],
                'sfwd-quiz' => [],
            ];

            $t['sfwd-lessons'][] = $lesson_id;
            $r['sfwd-lessons:' . $lesson_id] = [];
            $l[] = 'sfwd-lessons:' . $lesson_id;
        }

        $course_steps = [
            'h' => $h,
            't' => $t,
            'r' => $r,
            'l' => $l,

        ];

        return $course_steps;

    }

    private function get_lesson_quizzes($lesson_id)
    {
        return learndash_get_lesson_quiz_list($lesson_id);
    }

    private function create_sfwd_lessons($lessons)
    {

    }

    /**
     * Duplicate course_meta value from Dolly Course
     *
     * @param $course_id
     * @param $dollyCourse
     * @return array
     */
    private function duplicate_course_meta($course_id, $dollyCourse)
    {
        $courses_meta = [
            "_fl_builder_draft",
            "_fl_builder_draft_settings",
            "_fl_builder_data",
            "_fl_builder_data_settings",
            "_fl_builder_enabled",
            "fw_options",
            "wdm_video_thumb_url",
            "_sfwd-courses",
            "_iswp_custom_code",
            "learndash_certificate_options",
//            "ld_course_steps",
        ];

        $return = [];

        foreach ($courses_meta as $course_meta) {

            $post_meta = get_post_meta($dollyCourse->ID, $courses_meta);

            add_post_meta($course_id, $course_meta, $post_meta);

            $return[$course_meta] = $post_meta;
        }

        return $return;

    }

    /**
     * Duplicate lesson postmeta from the Template Lesson
     *
     * @param $lesson_id
     * @param $dollyLesson
     * @return array
     */
    private function duplicate_lesson_meta($lesson_id, $dollyLesson)
    {
        $lessons_meta = [
            '_sfwd-lessons',
            '_iswp_custom_code',
            'fw_options',
            '_fl_builder_draft',
            '_fl_builder_draft_settings',
            '_fl_builder_data',
            '_fl_builder_data_settings',
            '_fl_builder_enabled',
        ];

        $return = [];

        foreach ($lessons_meta as $lesson_meta) {

            $post_meta = get_post_meta($dollyLesson->ID, $lesson_meta);

            add_post_meta($lesson_id, $lesson_meta, $post_meta);

            $return[$lesson_meta] = $post_meta;
        }

        return $return;
    }

}
