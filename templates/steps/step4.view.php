<h3 class="cohort-title">Tags</h3>

<div class="oc-form-group">
<label for="oc-tag-id">Select Tags</label>
    <select name="oc-tag-id[]" id="oc-tag-id" class="oc-form-control-select select2" multiple="multiple">
        <?php if(isset($memberships)) : ?>
            <?php foreach($memberships as $data): ?>
                <?php
                    if(!empty($course_info)){
                        $selected = in_array($data['main_id'],$course_info[$_GET['p_id']]['post_meta']['tag_ids']) == true ? 'selected' : '';
                    } else {
                        $selected = "";
                    }
                ?>
            <?php  echo "<option value='{$data['main_id']}' {$selected}>{$data['tag_name']}</option> ";?>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<!-- <pre> -->
<?php
//print_r($course_info[$_GET['p_id']]['post_meta']['tag_ids']);
?>

<div class="oc-form-group">
    <label>Course Certificate</label>
    <select name="oc-course-cert" id="oc-course-cert" class="oc-form-control select2">
    <option value="">Select Course Certificate</option>
    <?php if(isset($courseCertificates)) : ?>
            <?php foreach($courseCertificates as $data): ?>
                <?php
                    if(!empty($course_info)){   
                        $selected = ($data->ID == $course_info[$_GET['p_id']]['post_meta']['certificate']) == true ? 'selected' : '';
                    } else {
                        $selected = "";
                    }
                ?>
            <?php echo "<option value='{$data->ID}' {$selected}>{$data->post_title}</option> ";?>
             <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>
</div>


<script type="text/javascript">
    $ = jQuery;

    $(document).ready(function () {
        $('#oc-tag-id').select2({
            placeholder: "Select tag",
            allowClear: true
        });
    });
</script>
