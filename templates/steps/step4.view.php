<h3 class="cohort-title">Tags</h3>

<div class="oc-form-group">
    <label for="oc-tag-id">Select Tags</label>
    <select name="oc-tag-id[]" id="oc-tag-id" class="oc-form-control-select select2" multiple="multiple">
        <?php if (isset($memberships)) : ?>
            <?php foreach ($memberships as $data): ?>
                <?php
                if (isset($course)) {
                    $selected = in_array($data['id'], $course['name']) == true ? 'selected' : '';
                } else {
                    $selected = "";
                }
                ?>
                <?php echo "<option value='{$data['id']}' {$selected}>{$data['id']} - {$data['name']}</option> "; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<div class="oc-form-group">
    <label>Course Certificate</label>
    <select name="oc-course-cert" id="oc-course-cert" class="oc-form-control select2">
        <option value="">Select Course Certificate</option>
        <?php if (isset($courseCertificates)) : ?>
            <?php foreach ($courseCertificates as $data): ?>
                <?php
                if (isset($course)) {
                    $selected = ($data->ID == $course['course-certificate']) == true ? 'selected' : '';
                } else {
                    $selected = "";
                }
                ?>
                <?php echo "<option value='{$data->ID}' {$selected}>{$data->post_title}</option> "; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('#oc-tag-id').select2({
            placeholder: "Select tag",
            allowClear: true,
            ajax: {
                url: (params) => {
                    return "<?php echo site_url() . _route('oc-tags', '')?>" + params.term;
                }
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                // Transforms the top-level key of the response object from 'items' to 'results'

                console.log(data);
                return {
                    results: data.items
                };
            },
            minimumInputLength: 2
        });
    });
</script>
