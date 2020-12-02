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
use Exception;
use AWC\Model\Posts;

class ClassroomController extends CoreController
{

    use LessonMeta, CourseMeta;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Store function for saving classrooms
     *
     * @param Request $request
     * @throws Exception
     */
    public function store(Request $request)
    {
        $arrLessons = [];

        $dates = $request->input('topic-date');
        $lessonNames = $request->input('lesson-name');
        $lessonIds = $request->input('lesson-id');

        $author = $request->input('online-tutor') <> "" ? $request->input('online-tutor') : get_current_user_id();

        //Get Dolly
        $dolly = new Posts;
        $dolly->find($request->input('course-content'));

        //Cloning process for the course
        $course = new Posts;
        $course->post_title = $request->input('course-title');
        $course->post_author = $author;
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

                    $this->lessonEchoLogger($lessonNames[$i], true);

                    add_post_meta($lesson_id, 'ld_course_steps', $this->create_ld_course_steps($arrLessons));
                } else {
                    $this->lessonEchoLogger($lessonNames[$i], false);
                }
            }

            //Create Course Steps post meta
            add_post_meta($course_id, 'ld_course_steps', $this->create_ld_course_steps($arrLessons));

            //Save the relationship as post meta
            add_post_meta($course_id, 'created-from-one-click', true);

            //Save Course certificate
            add_post_meta($course_id, 'course-cert', $request->input('oc-course-cert'), true);

        } else {
            echo "what just happened?";
        }


    }

    /**
     * Edit post render function
     *
     * @param Posts $posts
     * @throws Exception
     */
    public function edit(Posts $posts)
    {
        $getOptions = get_option('the-course-content');

        // Get course content data
        // This courseContent will serve as the course template for one-click
        $courseContent = $this->getCourseContents($getOptions);

        // Get memberships
        $memberships = $this->getCourseMemberships();

        // Online Tutor

        $onlineTutor = $this->getTutors();

        // Course Certificate
        $courseCertificates = $this->getCertificates();


        //Fill up the information that will be used for editing
        $course = [
            'course-template' => get_post_meta($posts->ID, 'one-click-template')[0],
            'course-title' => $posts->post_title,
            'author' => $posts->post_author,
            'course-tags' => explode(', ',get_post_meta($posts->ID, '_is4wp_access_tags')[0]),
            'course-certificate' => get_post_meta($posts->ID,'course-cert')[0],
            'awc_active_course' => get_post_meta($posts->ID, 'awc_active_course')[0],
            'collapse_replies_for_course' => get_post_meta($posts->ID, 'collapse_replies_for_course')[0],
            'awc_private_comments' => get_post_meta($posts->ID, 'awc_private_comments')[0],
            'email_daily_comment_digest' => get_post_meta($posts->ID, 'email_daily_comment_digest')[0],
            'cc_recipients' => get_post_meta($posts->ID, 'cc_recipients'),
        ];

        $courseModules = learndash_get_course_lessons_list($posts->ID);

        foreach ($courseModules as $courseModule) {
            $date = get_post_meta($courseModule['post']->ID, '_sfwd-lessons')[0]['sfwd-lessons_visible_after_specific_date'] <> ""
                ? get_post_meta($courseModule['post']->ID, '_sfwd-lessons')[0]['sfwd-lessons_visible_after_specific_date']
                : "";
            $course['lessons'][] = [
                'lesson-id' => $courseModule['post']->ID,
                'lesson-title' => $courseModule['post']->post_title,
                'date' => $date
            ];
        }


        (new View('steps/steps'))
            ->with('course', $course)
            ->with('courseContent', $courseContent)
            ->with('onlineTutor', $onlineTutor)
            ->with('courseCertificates', $courseCertificates)
            ->with('memberships', $memberships)
            ->render();
    }

    /**
     * Updates the classroom with the new values sent via the edit page.
     *
     * @param Request $request
     */
    public function update(Request $request)
    {

        $data = [
            'ID' => $request->input('post_id'),
            'post_title' => $request->input('course-title'),
            'post_author' => $request->input('online-tutor')
        ];
        wp_update_post($data);

        update_field('course-cert', $request->input('oc-course-cert'), $request->input('post_id'));

        $url = get_site_url() . "/wp-admin/admin.php?page=one-click-classroom-setup";
        wp_redirect($url);
    }
}
