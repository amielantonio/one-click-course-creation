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
     * Create entry
     * Load the course setup page with course content, memberships,
     * online tutor and course certificates
     *
     * @return array $courseContent
     * @return array $memberships
     * @return array $onlineTutor
     * @return array $courseCertificates
     * @return mixed|string
     * @throws Exception
     */

    public function create()
    {
        $getOptions = get_option('the-course-content');

        [
            '1234','12341'
        ];

        // This courseContent will serve as the course template for one-click
        $courseContent = $this->getCourseContents($getOptions);

        // Get memberships
        $memberships = $this->getCourseMemberships();

        // Online Tutor

        $onlineTutor = $this->getTutors();

        // Course Certificate
        $courseCertificates = $this->getCertificates();


        return (new View('steps/steps'))
            ->with('memberships', $memberships)
            ->with('courseContent', $courseContent)
            ->with('onlineTutor', $onlineTutor)
            ->with('courseCertificates', $courseCertificates)
            ->render();

    }

    /**
     *  Store function for saving classrooms
     *
     * @param Request $request
     * @return View
     * @throws Exception
     */
    public function store(Request $request)
    {
        if(empty($_POST)) wp_redirect( get_site_url() . "/wp-admin/admin.php?page=course-setup" );


        $arrLessons = [];

        $logger = [];
        $is_updated = false;
        $dates = $request->input('topic-date');
        $lessonNames = $request->input('lesson-name');
        $lessonIds = $request->input('lesson-id');
        $use_existing = $request->input('use-existing-val');
        $allowComments = $request->input('allow-comments-val');

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
            $logger['course'][$course_id] = [
                'type' => 'course',
                'id' => $course_id,
                'logger_type' => 'success',
                'message' => "course was created"
            ];

            // Post meta for the course
            $this->save_course_meta($course_id, $request, $dolly);

            // Once the main course is saved, process the lesson
            for ($i = 0; $i < count($lessonNames); $i++) {

                //Save the template lesson
                if( $use_existing[$i] && $use_existing[$i] <> "" ) {
                    $lesson_id = $lessonIds[$i];
                    $lesson = new Posts;
                    $lesson->find($lessonIds[$i]);

                    $arrLessons[] = $lessonIds[$i];

                    //Check if there is a date available for the current array node.
                    $lessonDate = $dates[$i] <> "" ? Carbon::createFromFormat('d F, Y g:i a', $dates[$i])->format('Y-m-d g:i a') : "";

                    //Add new leson meta
                    $new_lesson_meta = [
                        "sfwd-lessons_visible_after_specific_date" => $lessonDate
                    ];

                    add_post_meta($lesson_id, 'ld_course_' . $course_id, $course_id);

                    add_post_meta($lessonIds[$i], '_sfwd-lessons', $this->create_sfwd_lesson($lessonIds[$i], $lesson,$new_lesson_meta));

                    add_post_meta($lesson_id, 'ld_course_steps', $this->create_ld_course_steps($arrLessons));

                    $logger['lessons'][$lesson_id] = [
                        'type' => 'lesson',
                        'id' => $lesson_id,
                        'logger_type' => 'success',
                        'message' => "lesson was added from template course"
                    ];



                //Create a new lesson to be saved for the course settings
                } else {

                    // Save the lessons to swfd-lesson post type
                    $lesson = new Posts;
                    $dollyLesson = new Posts;

                    $dollyLesson->find($lessonIds[$i]);

                    //Populate Lesson Model with the info needed to insert the lesson in WP_Post
                    $lesson->post_title = $lessonNames[$i];
                    $lesson->post_author = $request->input('online-tutor') <> "" ? $request->input('online-tutor') : get_current_user_id();
                    $lesson->post_content =  $dollyLesson->post_content;
                    $lesson->post_excerpt = $dollyLesson->post_excerpt;
                    $lesson->post_status = "publish";
                    $lesson->post_type = $dollyLesson->post_type;
                    $lesson->comment_status = $allowComments[$i];

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

                        $logger['lessons'][$lesson_id] = [
                            'type' => 'lesson',
                            'id' => $lesson_id,
                            'logger_type' => 'success',
                            'message' => "lesson was created"
                        ];

                        add_post_meta($lesson_id, 'ld_course_steps', $this->create_ld_course_steps($arrLessons));
                    } else {
                        $logger['lessons'][$lesson_id] = [
                            'type' => '',
                            'id' => '',
                            'logger_type' => 'fail',
                            'message' => 'lesson was not created',
                        ];
                    }
                } // end if
            } // end foreach

            //Create Course Steps post meta
            add_post_meta($course_id, 'ld_course_steps', $this->create_ld_course_steps($arrLessons));

            //Save the relationship as post meta
            add_post_meta($course_id, 'created-from-one-click', true);

            //save template val
            add_post_meta($course_id, 'save-template-val', $use_existing);

            //Save Course certificate
            add_post_meta($course_id, 'learndash_certificate_options', $request->input('oc-course-cert'), true);

        } else {
            $logger['course'][$course_id] = [
                'type' => 'course',
                'id' => $course_id,
                'logger_type' => 'fail',
                'message' => "course was not created"
            ];
        }

        return (new View('templates/store'))
            ->with('course_id', $course_id)
            ->with('lessons', $arrLessons)
            ->with('is_updated', $is_updated)
            ->with('logger', $logger)
            ->render();
    }

    /**
     *  Edit post render function
     *
     * @param Posts $posts
     * @return mixed|string
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
            'ID' => $posts->ID,
            'post_status' => $posts->post_status,
            'course-template' => get_post_meta($posts->ID, 'one-click-template')[0],
            'course-title' => $posts->post_title,
            'author' => $posts->post_author,
            'course-tags' => explode(', ', get_post_meta($posts->ID, '_is4wp_access_tags')[0]),
            'course-certificate' => get_post_meta($posts->ID, '_sfwd-courses')[0]['sfwd-courses_certificate'],
            'awc_active_course' => get_post_meta($posts->ID, 'awc_active_course')[0],
            'collapse_replies_for_course' => get_post_meta($posts->ID, 'collapse_replies_for_course')[0],
            'awc_private_comments' => get_post_meta($posts->ID, 'awc_private_comments')[0],
            'email_daily_comment_digest' => get_post_meta($posts->ID, 'email_daily_comment_digest')[0],
            'cc_recipients' => get_post_meta($posts->ID, 'cc_recipients'),
            'existing-val' => get_post_meta($posts->ID, 'save-template-val')[0]
        ];

        $courseModules = learndash_get_course_lessons_list($posts->ID);

        foreach ($courseModules as $courseModule) {
            $date = get_post_meta($courseModule['post']->ID, '_sfwd-lessons')[0]['sfwd-lessons_visible_after_specific_date'] <> ""
                ? get_post_meta($courseModule['post']->ID, '_sfwd-lessons')[0]['sfwd-lessons_visible_after_specific_date']
                : "";
            $course['lessons'][] = [
                'lesson-id' => $courseModule['post']->ID,
                'lesson-title' => $courseModule['post']->post_title,
                'comment_status' => $courseModule['post']->comment_status,
                'date' => $date
            ];
        }

        return (new View('steps/steps'))
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
     * @param Posts $posts
     * @throws Exception
     */
    public function update(Request $request, Posts $posts)
    {
        $id = $request->input('post_id');
        $dates = $request->input('topic-date');
        $lessonNames = $request->input('lesson-name');
        $lessonIds = $request->input('lesson-id');
        $use_existing = $request->input('use-existing-val');
        $allowComments = $request->input('allow-comments-val');
        $is_updated = true;

        $courseData = [
            'ID' => $id,
            'post_title' => $request->input('course-title'),
            'post_author' => $request->input('online-tutor'),
            'post_status' => $request->input('course-post_status')
        ];
        if ($courseID = wp_update_post($courseData)) {
            $this->courseEchoLogger($request->input('course-title'), true);

            for($i = 0; $i < count($lessonNames); $i++) {

                $lesson = new Posts;

                $lesson->find($lessonIds[$i]);

                $lessonData = [
                    'ID' => $lessonIds[$i],
                    'post_title' => $lessonNames[$i],
                    'post_name' => sanitize_title($lessonNames[$i]),
                    'comment_status' => $allowComments[$i],
                ];

                if( $lessonID = wp_update_post($lessonData)){
                    //Check if there is a date available for the current array node.
                    $lessonDate = $dates[$i] <> "" ? Carbon::createFromFormat('d F, Y g:i a', $dates[$i])->format('Y-m-d g:i a') : "";

                    //Add new leson meta
                    $new_lesson_meta = [
                        "sfwd-lessons_visible_after_specific_date" => $lessonDate
                    ];

                    update_post_meta($lessonID, '_sfwd-lessons', $this->create_sfwd_lesson($lessonID, $lesson, $new_lesson_meta));

                    $this->lessonEchoLogger($lessonNames[$i], true);
                }
            }

            /*
             * Save the course meta
             */

            update_field('course-cert', $request->input('oc-course-cert'), $request->input('post_id'));

            update_field('_is4wp_access_tags', implode(', ', $request->input('oc-tag-id')), $request->input('post_id'));


            // Update Course Settings
            $private_commenting = ($request->input('awc_private_comments') <> "") ? "Allow private comments" : "";
            $collapse_replies = ($request->input('collapse_replies_for_course') <> "") ? "Collapse replies for this course" : "";

            update_field('awc_active_course', $request->input('awc_active_course'), $request->input('post_id'));
            update_field('email_daily_comment_digest', $request->input('email_daily_comment_digest'), $request->input('post_id'));
            update_field('cc_recipients', $request->input('cc_recipients'), $request->input('post_id'));
            update_field('awc_private_comments', $private_commenting, $request->input('post_id'));
            update_field('collapse_replies_for_course', $collapse_replies, $request->input('post_id'));
            update_post_meta($id, 'save-template-val', $use_existing);


            $certificate = $request->input('oc-course-cert');
            $sfwd_courses = get_post_meta($id, '_sfwd-courses')[0];
            $sfwd_courses['sfwd-courses_certificate'] = $certificate;

            update_post_meta($id, '_sfwd-courses', $sfwd_courses);



        }

        $url = get_site_url() . "/wp-admin/admin.php?page=one-click-classroom-setup";
        wp_redirect($url);

    }

    /**
     * Delete Classroom
     *
     * @param Request $request
     * @throws Exception
     */
    public function delete(Request $request)
    {
        $course = new Posts;
        $id = $request->input('id');

        $data = $course->delete($id);
        echo json_encode($data);
        die();
    }

    /**
     * Put the classroom to the trash
     *
     * @param Posts $posts
     */
    public function trash(Posts $posts)
    {
        $courseData = [
            'ID' => $posts->ID,
            'post_status' => 'trash'
        ];

        if( $courseID = wp_update_post($courseData)){

            $url = get_site_url() . "/wp-admin/admin.php?page=one-click-classroom-setup&trashnotif=on&trashid={$posts->ID}";

            wp_redirect($url);
            exit(0);
        }

    }


    public function ajaxTags($tags) {

        echo json_encode($tags);
        die();
    }
}
