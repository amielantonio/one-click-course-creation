<h1 class="_m-b--50">Plugin Settings</h1>

<div class="oc-container">
    <div class="oc-collection-container">

        <div class="container-body">

            <form action="<?= _route('save-settings')?>" method="post" class="oc-form">
                <div class="oc-form-group">
                    <label for="oc-content-parent-id">Course content parent ID</label>
                    <select name="oc-content-parent-id[]" id="oc-content-parent-id" class="oc-form-control-select" multiple="multiple">

                        <?php if(isset($option) && !empty($option) ): ?>
                            <?php foreach($option as $key => $value):?>
                                <option value="<?php echo $key?>" selected="selected"><?php echo $value?></option>
                            <?php endforeach;?>
                        <?php endif; ?>

                        <?php foreach($courses as $course) :?>
                            <option value="<?php echo $course->ID?>"><?php echo $course->ID . " - " . $course->post_title?></option>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="oc-form-group">
                    <label for="oc-exclude-module-keywords">Exclude module keywords</label>
                    <p class="information">Separate keywords with comma (,)</p>
                    <textarea name="oc-exclude-module-keywords" id="oc-exclude-module-keywords" class="oc-form-control" cols="30" rows="10"><?php echo isset($excludeKeywords) ? $excludeKeywords : ""?></textarea>
                </div>

                <div class="cohort-buttons oc-button-group align-center">
                    <button id="btn-classroom" class="oc-btn oc-btn--primary" type="submit">Save Settings</button>
                </div>
            </form>

        </div>


    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#oc-content-parent-id').select2({
            placeholder: "Select course content",
            allowClear: true
        });
    });
</script>

