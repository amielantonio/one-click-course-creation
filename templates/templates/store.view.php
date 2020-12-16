<?php if (isset($logger)) : $ctr = 0; ?>
    <?php foreach ($logger as $id => $type) : $ctr++;?>
        <div class="">
            <?php if($ctr <= 2 ) :?>
            <div class="">
                <h3><?php echo $id == 'course' ? "Course " : "Lessons " ?><?php echo (isset($is_updated) && $is_updated) ? "Updated" : "Created" ?></h3>
            </div>
            <?php endif; ?>
            <div>
                <?php if ($id == "course") : ?>
                    <?php foreach ($logger['course'] as $course_id => $course) : ?>
                        <div class="echo-logger _with-toolbox _s_b">
                            <div class="echo-logger-text">
                                <span class="logger_course_name success"><?php echo $course_id." - ".get_post($course_id)->post_title; ?></span> - <?php echo $course['message'] ?>
                            </div>
                            <div class="toolbox">
                                <a href="#" class="tool">View</a>
                                <a href="#" class="tool">Learndash</a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>

                <?php  if($id == "lessons") : ?>
                    <?php foreach ($logger['lessons'] as $lesson_id => $lesson) : ?>
                        <div class="echo-logger _with-toolbox _s_b">
                            <div class="echo-logger-text">
                                <span class="logger_course_name success"><?php echo $lesson_id." - ".get_post($lesson_id)->post_title; ?></span> - <?php echo $lesson['message'] ?>
                            </div>
                            <div class="toolbox">
                                <a href="#" class="tool">View</a>
                                <a href="#" class="tool">Learndash</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($course_id)): $course_passed = get_post($course_id) ?>
    <div class="_m-t--40">
        <a href="<?php echo site_url() . "/courses/{$course_passed->post_name}" ?>" class="oc-btn oc-btn--primary">View
            Course Frontend</a>
        <a href="<?php echo site_url() . "/wp-admin/post.php?post={$course_id}&action=edit" ?>"
           class="oc-btn oc-btn--secondary">View Course in Learndash</a>
        <a href="<?php echo _channel('classroom-edit', ['posts' => $course_id], 'course-setup') ?>"
           class="oc-btn oc-btn--secondary">View Course in One-Click</a>
    </div>
<?php endif; ?>

