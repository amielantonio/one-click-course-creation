<h3 class="cohort-title">Tags</h3>

<div class="oc-form-group">
    <label>Require Tag IDs</label>
    <select name="oc-tag-id" id="oc-tag-id" class="oc-form-control select2">
    <option>Select Tag</option>
        <?php if(isset($memberships)) : ?>
            <?php foreach($memberships as $data): ?>
            <?php   echo "<option value='{$data['main_id']}'>{$data['tag_name']}</option> ";?>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<div class="oc-form-group">
    <label>Course Group</label>
    <select name="oc-course-groups" id="oc-course-groups" class="oc-form-control select2">
        <option>Select Course Group</option>
        <?php if(isset($courseGroups)) : ?>
            <?php foreach($courseGroups as $data): ?>
            <?php   echo "<option value='{$data->ID}'>{$data->post_title}</option> ";?>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>