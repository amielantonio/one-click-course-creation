<?php

namespace AWC\Traits;

trait LessonMeta
{

    /**
     * Course echo logger, renders whether the course was created or not.
     *
     * @param $course_name
     * @param bool $is_created
     */
    private function lessonEchoLogger( $lesson_name, $is_created = true )
    {
        if( $is_created ){
            echo "<div class='echo-logger logger_lesson'>
                    <p class='echo-logger-text logger_lesson_text'>
                        <span class='logger_lesson_name success'>{$lesson_name}</span> - lesson created
                    </p>
                  </div>";
        } else {
            echo "<div class='echo-logger logger_lesson'>
                    <p class='echo-logger-text logger_lesson_text'>
                        <span class='logger_lesson_name danger'>{$lesson_name}</span> - lesson created
                    </p>
                  </div>";
        }
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
     * Duplicate lesson postmeta from the Template Lesson
     *
     * @param $lesson_id
     * @param $dollyLesson
     * @return array
     */
    function duplicate_lesson_meta($lesson_id, $dollyLesson)
    {
        $lessons_meta = [
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

            $id = $dollyLesson->ID;

            add_post_meta($lesson_id, $lesson_meta, get_post_meta($id, $lesson_meta));

            $return[$lesson_meta] = get_post_meta($id, $lesson_meta);
        }

        return $return;
    }

    /**
     * Create the Sfwd Lessons that is required by the Learndash
     *
     * @param $lesson_id
     * @param $dollyLesson
     * @param array $new_lesson_meta
     * @return array
     */
    function create_sfwd_lesson( $lesson_id, $dollyLesson, $new_lesson_meta = [] )
    {
        //Get Dolly Lesson Meta for SFWD lessons
        $dollyLessonMeta = get_post_meta($dollyLesson->ID, '_sfwd-lessons')[0];

        //Instantiate default value of the SFWD Lessons Meta
        $lesson_meta = [
            'sfwd-lessons_lesson_materials' => "",
            'sfwd-lessons_forced_lesson_time' => "",
            'sfwd-lessons_lesson_assignment_upload' => "",
            'sfwd-lessons_auto_approve_assignment' => "",
            'sfwd-lessons_assignment_upload_limit_count' => 1,
            'sfwd-lessons_lesson_assignment_deletion_enabled' => "",
            'sfwd-lessons_lesson_assignment_points_enabled' => "",
            'sfwd-lessons_lesson_assignment_points_amount' => 0,
            'sfwd-lessons_assignment_upload_limit_extensions' => "",
            'sfwd-lessons_assignment_upload_limit_size' => "",
            'sfwd-lessons_sample_lesson' => [],
            'sfwd-lessons_visible_after' => "0",
            'sfwd-lessons_visible_after_specific_date' => 0,
        ];

        // Combine results for parent lesson sfwd-lessons with the default values of the sfwd-lessons,
        // check whether there is a result for the parent ID else get the default one instead.
        if (!empty($dollyLessonMeta)) {
            foreach ($lesson_meta as $key => $meta) {
                $lesson_meta[$key] = (isset($dollyLessonMeta[$key])) ? $dollyLessonMeta[$key] : $lesson_meta[$key];
            }
            //Get other meta keys that are not in the default, then merge it with the current lesson meta.
            $getDiff = array_diff($lesson_meta, $dollyLessonMeta);

            $lesson_meta = array_merge($lesson_meta, $getDiff);
        }

        // Combine result of the new lesson meta with values to the lesson meta then return everything.
        if (count($new_lesson_meta) > 0) {
            foreach ($lesson_meta as $key => $meta) {
                $lesson_meta[$key] = (isset($new_lesson_meta[$key])) ? $new_lesson_meta[$key] : $lesson_meta[$key];
            }
        }

        return $lesson_meta;
    }


}