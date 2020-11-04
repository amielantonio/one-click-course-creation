<h3 class="cohort-title">Select a Course</h3>

<div class="oc-form-group">
    <label>Course Content</label>
    <select name="course-content" class="oc-form-control select2" id="course-content" required="required">
        <option>Select Course</option>
        <?php if(isset($courseContent)) : ?>
            <?php foreach( $courseContent as $key => $value) : ?>
                <option id="option-<?php echo $key?>" value="<?php echo $key?>"
                        data-lessons='<?php echo json_encode($value['lessons']);?>'
                        data-course-meta='<?php echo json_encode($value['post_meta']);?>'
                        data-course-title="<?php echo $value['course_name']?>">
                    <?php echo $key . " - " . $value['course_name']?>
                </option>
            <?php endforeach;?>
        <?php endif; ?>
    </select>
</div>

<div class="oc-form-group">
    <label>Course Title</label>
    <input type="text" class="oc-form-control" name="course-title" id="course-title" placeholder="Add New Course Title">
</div>

<div class="oc-form-group">
    <label>Online Tutor</label>
    <select name="online-tutor" class="oc-form-control select2" id="online-tutor">
        <option value="">Select Tutor</option>
        <?php if(isset($onlineTutor)) : ?>
            <?php foreach($onlineTutor as $data): ?>
            <?php   echo "<option value='{$data->ID}'>{$data->display_name} ({$data->user_email})</option> ";?>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>
