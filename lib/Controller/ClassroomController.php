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

            //Save Course certificate
            add_post_meta($course_id, 'course-cert',$request->input('oc-course-cert'),true);

        } else {
            echo "what just happened?";
        }


    }

    /**
     *
     * Edit View
     * @param Posts $posts
     * @throws \Exception
     */
    public function edit(Posts $posts)
    {
        global $wpdb;

        $courseContent = [];
        $getOptions = get_option('the-course-content');
        
        //Fill up the information that will be used for editing
        $course = [
            'course-template' => get_post_meta($posts->ID, 'one-click-template' )[0],
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

        foreach($courseModules as $courseModule) {
            $date = get_post_meta($courseModule['post']->ID, '_sfwd-lessons' )[0]['sfwd-lessons_visible_after_specific_date'] <> ""
                ? get_post_meta($courseModule['post']->ID, '_sfwd-lessons' )[0]['sfwd-lessons_visible_after_specific_date']
                : "";
            $course['lessons'][] = [
                'lesson-id' => $courseModule['post']->ID,
                'lesson-title' => $courseModule['post']->post_title,
                'date' => $date
            ];
        }
        
        // Get course content data
        // This courseContent will serve as the course template for one-click
        if(!empty($getOptions)) {
            foreach($getOptions as $getOption) {
                $courseSelected = get_post($getOption);
                $courseContent[$courseSelected->ID]['course_name'] = $courseSelected->post_title;

                $lessons = learndash_get_course_lessons_list($getOption);

                foreach($lessons as $lesson) {
                    $courseContent[$courseSelected->ID]['lessons'][] = [
                        'lesson-id' => $lesson['post']->ID,
                        'lesson-title' => $lesson['post']->post_title,
                    ];

                    $courseContent[$courseSelected->ID]['post_meta'] = [
                        'awc_active_course' => get_post_meta($getOption, 'awc_active_course')[0],
                        'collapse_replies_for_course' => get_post_meta($getOption, 'collapse_replies_for_course')[0],
                        'awc_private_comments' => get_post_meta($getOption, 'awc_private_comments')[0],
                        'email_daily_comment_digest' => get_post_meta($getOption, 'email_daily_comment_digest')[0],
                        'cc_recipients' => get_post_meta($getOption, 'cc_recipients'),
                        'tag_ids' => explode(', ',get_post_meta($getOption, '_is4wp_access_tags')[0]),
                        'certificate' => get_post_meta($getOption, '_sfwd-courses')[0]['sfwd-courses_certificate'],
                        'excluded_keywords' => get_option('exclude-module-keywords'),
                    ];
                }
            }
        }

        // Get memberships
        $memberium = get_option('memberium');
        $memberships = [];
        if(isset($memberium['memberships'])){
            // GET THE TAG LIST
            $tags = [];
            $table = 'memberium_tags';
            $appname = "lf159"; # memberium_tags table appname field

            $sql = "SELECT id, name FROM {$table} WHERE `appname` = '{$appname}' ORDER BY category, name ";
            $result = $wpdb->get_results($sql, ARRAY_A);
            foreach ($result as $data) {
                $tags['mc'][$data['id']] = $data['name'];
            }

            $tags = $tags['mc'];
            // INCLUDE TAG ON LIST
            foreach ($memberium['memberships'] as $key => $data) {
                $tag = !empty($tags[$key]) ? $tags[$key]." ({$key})" : '(Missing Tag)';
                $memberium['memberships'][$key]['tag_name']  =  $tag;
            }

            $memberships = $memberium['memberships'];
        }


        // Online Tutor
        $onlineTutor =  get_users([
            'role__in' => [ 'Administrator', 'group_leader'],
            'fields'   => ['ID','user_email','display_name'],
            'orderby'  => 'display_name'
        ]);

        $posts = new Posts;
        // Course Certificate
        $courseCertificates = $posts->select(['ID, post_title'])->where('post_type', 'sfwd-certificates')->orderBy('post_title')->results();


        (new View('steps/steps'))
            ->with('course', $course)
            ->with('courseContent', $courseContent)
            ->with('onlineTutor',$onlineTutor)
            ->with('courseCertificates',$courseCertificates)
            ->with('memberships',$memberships)
            ->render();
    }

    public function update(Request $request) {
        
        $data = [
            'ID'           => $request->input('post_id'),
            'post_title'   => $request->input('course-title'),
            'post_author'  => $request->input('online-tutor')
        ];
        wp_update_post($data);

        update_field('course-cert', $request->input('oc-course-cert'), $request->input('post_id'));
        update_field('_is4wp_access_tags', implode(', ', $request->input('oc-tag-id')), $request->input('post_id') );


        // Update Course Settings
        $private_commenting = ($request->input('awc_private_comments') <> "") ? "Allow private comments" : "";
        $collapse_replies = ($request->input('collapse_replies_for_course') <> "") ? "Collapse replies for this course" : "";

        update_field('awc_active_course', $request->input('awc_active_course'), $request->input('post_id'));
        update_field('email_daily_comment_digest', $request->input('email_daily_comment_digest'), $request->input('post_id'));
        update_field('cc_recipients', $request->input('cc_recipients'), $request->input('post_id'));
        update_field('awc_private_comments', $private_commenting, $request->input('post_id'));
        update_field('collapse_replies_for_course', $collapse_replies, $request->input('post_id'));
        
        $url = get_site_url()."/wp-admin/admin.php?page=one-click-classroom-setup";
        wp_redirect( $url );
        
    }
}
