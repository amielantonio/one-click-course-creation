<h3 class="cohort-title">Select a Course</h3>

<div class="oc-form-group">
    <label>Course Content</label>
    <select name="course-content" class="oc-form-control select2" id="course-content">
        <option>Select Course</option>
        <?php if(isset($courseContent)) : ?>
            <?php foreach( $courseContent as $key => $value) : ?>
                <option id="option-<?php echo $key?>" value="<?php echo $key?>"
                        data-lessons='<?php echo json_encode($value['lessons']);?>'
                        data-course-meta='<?php echo json_encode(($value['post_meta']));?>'
                        data-course-title="<?php echo $value['course_name']?>">
                    <?php echo $key . " - " . $value['course_name']?>
                </option>
            <?php endforeach;?>
        <?php endif; ?>
    </select>
</div>

<div class="oc-form-group">
    <label>Course Title</label>
    <input type="text" class="oc-form-control" id="course-title" placeholder="Add New Course Title">
</div>

<div class="oc-form-group">
    <label>Online Tutor</label>
    <select name="" class="oc-form-control select2" id="online-tutor">
        <option>Select Course</option>
        <option>Creative Writing Stage 1</option>
        <option>Freelance Writing Stage 1</option>
        <option>Short Story Essentials</option>
        <option>Novel Writing Essentials</option>
        <option>Write Your Novel</option>
    </select>
</div>
