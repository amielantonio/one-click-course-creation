<?php

namespace AWC\Traits;

trait CourseMeta
{

    /**
     * Duplicate course_meta value from Dolly Course
     *
     * @param $course_id
     * @param $dollyCourse
     * @return array
     */

    function duplicate_course_meta($course_id, $dollyCourse)
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

    function create_sfwd_course()
    {
        $course_meta = [
            "sfwd-courses_ld_course_category" => [],
            "sfwd-courses_ld_course_tag" => [],
            "sfwd-courses_course_materials" => "",
            "sfwd-courses_course_price_type" => "free",
            "sfwd-courses_custom_button_label" => "",
            "sfwd-courses_custom_button_url" => "",
            "sfwd-courses_course_price" => "",
            "sfwd-courses_course_prerequisite_enabled" => "off",
            "sfwd-courses_course_prerequisite" => [],
            "sfwd-courses_course_prerequuisite_compare" => "ANY",
            "sfwd-courses_course_points_enabled" => "",
            "sfwd-courses_course_points" => 0,
            "sfwd-courses_course_points_access" => 0,
            "sfwd-courses_course_disable_lesson_progression" => "on",
            "sfwd-courses_expire_access" => "",
            "sfwd-courses_expire_access_days" => "",
        ];
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

        add_post_meta($course_id, '_is4wp_access_tags', implode(', ', $request->input('oc-tag-id')));
        add_post_meta($course_id, 'awc_active_course', $request->input('awc_active_course'));
        add_post_meta($course_id, 'email_daily_comment_digest', $request->input('email_daily_comment_digest'));
        add_post_meta($course_id, 'cc_recipients', $request->input('cc_recipients'));
        add_post_meta($course_id, 'awc_private_comments', $private_commenting);
        add_post_meta($course_id, 'collapse_replies_for_course', $collapse_replies);
        $this->duplicate_course_meta($course_id, $dolly);
    }
}
