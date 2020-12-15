<div class="">
    <div class="">
        <h4>Course <?php echo (isset($is_updated) && $is_updated) ? "Updated" : "Created" ?></h4>
    </div>
    <div>

    </div>
</div>

<div class="">
    <div class="">
        <h4>Lessons <?php echo (isset($is_updated) && $is_updated) ? "Updated" : "Created" ?></h4>
    </div>
    <div>
        <?php if( isset($lessons) ) : ?>
            <?php foreach($lessons as $lesson) : ?>

            <?php endforeach;?>
        <?php endif;?>
    </div>
</div>

<?php if(isset($course_id)): $course_passed = get_post($course_id)?>
    <div class="_m-t--40">
        <a href="<?php echo site_url()."/courses/{$course_passed->post_name}" ?>" class="oc-btn oc-btn--primary">View Course Frontend</a>
        <a href="<?php echo site_url()."/wp-admin/post.php?post={$course_id}&action=edit" ?>" class="oc-btn oc-btn--secondary">View Course in Learndash</a>
        <a href="<?php echo _channel('classroom-edit', ['posts'=>$course_id], 'course-setup')?>" class="oc-btn oc-btn--secondary">View Course in One-Click</a>
    </div>
<?php endif; ?>

