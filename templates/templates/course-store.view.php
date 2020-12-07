<div class="">
    <div class="">

    </div>
</div>
<?php if(isset($course)): ?>
<div class="">
    <a href="<?php echo site_url()."/courses/{$course->post_name}" ?>" class="oc-btn oc-btn--primary">View Course Frontend</a>
    <a href="<?php echo site_url()."/wp-admin.post.php?post={$course->ID}&action=edit" ?>" class="oc-btn oc-btn--secondary">View Course in Learndash</a>
</div>
<?php endif; ?>
