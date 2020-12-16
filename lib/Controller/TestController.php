<?php
namespace AWC\Controller;

use AWC\Helpers\Func;
use AWC\Helpers\View;
use AWC\Core\Request;
use AWC\Core\CoreController;
use Router;
use AWC\Model\Posts;

class TestController extends CoreController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function classroomStore(Posts $posts)
    {
        //Fill up the information that will be used for editing
        $is_updated = false;
        $logger = [];


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
        ];

        $logger['course'][$course['ID']] = [
            'type' => 'course',
            'id' => $course['ID'],
            'logger_type' => 'success',
            'message' => "course was created"
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

            $logger['lessons'][$courseModule['post']->ID] = [
                'type' => 'lesson',
                'id' => $courseModule['post']->ID,
                'logger_type' => 'success',
                'message' => "lesson was created"
            ];
        }

        return (new View('templates/store'))
            ->with('course_id', $course['ID'])
            ->with('lessons', $course['lessons'])
            ->with('is_updated', $is_updated)
            ->with('logger', $logger)
            ->render();

    }

}
