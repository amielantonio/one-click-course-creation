<?php

namespace AWC\Traits;

use AWC\Model\Posts;
use Exception;

trait CourseMeta
{

    /**
     * Course echo logger, renders whether the course was created or not.
     *
     * @param $course_name
     * @param bool $is_created
     */
    private function courseEchoLogger( $course_name, $is_created = true )
    {
        if( $is_created ){
            echo "<div class='logger_course'>
                    <p class='logger_course_text'>
                        <span class='logger_course_name'>{$course_name}</span> course created
                    </p>
                  </div>";
        } else {
            echo "<div class='logger_course'>
                    <p class='logger_course_text'>
                        <span class='logger_course_name'>{$course_name}</span> course created
                    </p>
                  </div>";
        }
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
            "_sfwd-courses",
            "_fl_builder_draft",
            "_fl_builder_draft_settings",
            "_fl_builder_data",
            "_fl_builder_data_settings",
            "_fl_builder_enabled",
            "fw_options",
            "wdm_video_thumb_url",
            "_iswp_custom_code",
            "learndash_certificate_options",
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
     * Create ld course steps for Learndash relations
     *
     * @param $lesson_ids
     * @return array
     */
    private function create_ld_course_steps($lesson_ids)
    {
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

        return [
            'h' => $h,
            't' => $t,
            'r' => $r,
            'l' => $l,
            ​
        ];
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

        $accessTags = 12626;
        $tags = in_array($accessTags, $request->input('oc-tag-id')) ? $request->input('oc-tag-id') : array_push($request->input('oc-tag-id'), $accessTags);

//        add_post_meta($course_id, '_is4wp_access_tags', implode(', ', $tags));
        add_post_meta($course_id, '_is4wp_access_tags', implode(', ', $request->input('oc-tag-id')));
        add_post_meta($course_id, 'awc_active_course', $request->input('awc_active_course'));
        add_post_meta($course_id, 'email_daily_comment_digest', $request->input('email_daily_comment_digest'));
        add_post_meta($course_id, 'cc_recipients', $request->input('cc_recipients'));
        add_post_meta($course_id, 'awc_private_comments', $private_commenting);
        add_post_meta($course_id, 'collapse_replies_for_course', $collapse_replies);
        $this->duplicate_course_meta($course_id, $dolly);
    }


    /**
     * Get course certificates
     *
     * @return array|object|null
     * @throws Exception
     */
    public function getCertificates()
    {
        $posts = new Posts;

        return $posts->select(['ID, post_title'])->where('post_type', 'sfwd-certificates')->orderBy('post_title')->results();
    }

    /**
     * Get tutors
     *
     * @return mixed
     */
    public function getTutors()
    {
        return get_users([
            'role__in' => [ 'Administrator', 'group_leader'],
            'fields'   => ['ID','user_email','display_name'],
            'orderby'  => 'display_name'
        ]);
    }


    public function getCourseMemberships()
    {
        global $wpdb;

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

        return $memberships;
    }

    /**
     * Get Course Contents
     *
     * @param $courses
     * @return array | boolean
     */
    public function getCourseContents( $courses )
    {

        $courseContent = [];
        if(!is_array($courses)) {
            $courses[] = $courses;
        }

        if(!empty($courses)) {
            return false;
        }

        foreach($courses as $course) {
            $courseSelected = get_post($course);
            $courseContent[$courseSelected->ID]['course_name'] = $courseSelected->post_title;

            $lessons = learndash_get_course_lessons_list($course);

            foreach($lessons as $lesson) {
                $courseContent[$courseSelected->ID]['lessons'][] = [
                    'lesson-id' => $lesson['post']->ID,
                    'lesson-title' => $lesson['post']->post_title,
                ];

                $courseContent[$courseSelected->ID]['post_meta'] = [
                    'awc_active_course' => get_post_meta($course, 'awc_active_course')[0],
                    'collapse_replies_for_course' => get_post_meta($course, 'collapse_replies_for_course')[0],
                    'awc_private_comments' => get_post_meta($course, 'awc_private_comments')[0],
                    'email_daily_comment_digest' => get_post_meta($course, 'email_daily_comment_digest')[0],
                    'cc_recipients' => get_post_meta($course, 'cc_recipients'),
                    'tag_ids' => explode(', ',get_post_meta($course, '_is4wp_access_tags')[0]),
                    'certificate' => get_post_meta($course, '_sfwd-courses')[0]['sfwd-courses_certificate'],
                    'excluded_keywords' => get_option('exclude-module-keywords'),
                ];
            }
        }

        return $courseContent;
    }
}