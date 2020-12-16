<?php if(isset($logger)) : ?>
    <?php foreach($logger as $id => $type) : ?>
        <div class="">
            <div class="">
                <h3><?php echo $type['type'] == 'course' ? "Course" : "Lessons"?> <?php echo (isset($is_updated) && $is_updated) ? "Updated" : "Created" ?></h3>
            </div>
            <div>
<!--                --><?php //if( isset($type['']) ) : ?>
<!--                    --><?php //foreach($lessons as $lesson) : ?>
<!---->
<!--                    --><?php //endforeach;?>
<!--                --><?php //endif;?>
            </div>
        </div>
    <?php endforeach;?>
<?php endif; ?>

<?php if(isset($logger)) : ?>
<?php var_dump($logger); ?>
<?php endif; ?>

<?php if(isset($course_id)): $course_passed = get_post($course_id)?>
    <div class="_m-t--40">
        <a href="<?php echo site_url()."/courses/{$course_passed->post_name}" ?>" class="oc-btn oc-btn--primary">View Course Frontend</a>
        <a href="<?php echo site_url()."/wp-admin/post.php?post={$course_id}&action=edit" ?>" class="oc-btn oc-btn--secondary">View Course in Learndash</a>
        <a href="<?php echo _channel('classroom-edit', ['posts'=>$course_id], 'course-setup')?>" class="oc-btn oc-btn--secondary">View Course in One-Click</a>
    </div>
<?php endif; ?>

