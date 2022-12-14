<h3 class="cohort-title <?php echo isset($course) ? "title-with-toolbox _j_c--sb _a_i--c" : "" ?>">
    <?php echo isset($course) ? "Course: " . $course['ID'] : "Select a Course"?>

    <?php if(isset($course)) : ?>
        <small class="oc-form-group form-row" style="width: 20%;">
            <label for="course-post_status">Course Status</label>
            <select name="course-post_status" id="course-post_status">
                <option value="publish" <?php echo ($course['post_status'] == 'publish') ? "selected='selected'" : "" ?>>Publish</option>
                <option value="draft" <?php echo ($course['post_status'] == 'draft') ? "selected='selected'" : "" ?>>Draft</option>
            </select>

        </small>
    <?php endif; ?>
</h3>

<div class="oc-form-group">
    <label>Course Content  <span class="important">*</span></label>
    <select name="course-content" class="oc-form-control select2" id="course-content" required="required">
        <option value="">Select Course</option>
        <?php if(isset($courseContent)) : ?>
            <?php foreach( $courseContent as $key => $value) : ?>

                <?php $selected = (isset($course) && $key==$course['course-template']) ? "selected='selected'" : "" ?>

                <option id="option-<?php echo $key?>" value="<?php echo $key?>"
                        data-lessons='<?php echo json_encode($value['lessons']);?>'
                        data-course-meta='<?php echo json_encode($value['post_meta']);?>'
                        data-course-title="<?php echo $value['course_name']?>"
                    <?php echo $selected ?>>
                    <?php echo $key . " - " . $value['course_name']?>
                </option>
            <?php endforeach;?>
        <?php endif; ?>
    </select>
</div>

<?php if(!empty($course_info)){ ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#course-content').trigger("change");
        });
    </script>
<?php } ?>


<div class="oc-form-group">
    <label for="course-title">Course Title <span class="important">*</span></label>

    <?php
    $courseTitle = isset($course['course-title']) ? $course['course-title'] : "";
    ?>

    <input type="text" class="oc-form-control" name="course-title" id="course-title" value="<?php echo $courseTitle?>"  placeholder="Add New Course Title" required="required">

</div>


<div class="oc-form-group">
    <label>Online Tutor</label>
    <select name="online-tutor" class="oc-form-control select2" id="online-tutor">
    <pre>
        <option value="">Select Tutor</option>
        <?php if(isset($onlineTutor)) : ?>
            <?php foreach($onlineTutor as $data): ?>

                <?php $selectedAuthor = (isset($course) && $data->ID==$course['author']) ? "selected='selected'" : "" ?>

                <?php   echo "<option value='{$data->ID}' {$selectedAuthor}>{$data->display_name} ({$data->user_email})</option> ";?>

            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>


