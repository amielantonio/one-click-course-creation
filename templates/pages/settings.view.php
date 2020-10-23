<h1 class="_m-b--50">Plugin Settings</h1>

<div class="oc-container">
    <div class="oc-collection-container">

        <div class="container-body">

            <form action="<?= _route('save-settings')?>" method="post" class="oc-form">
                <div class="oc-form-group">
                    <label for="oc-content-parent-id">Course content parent ID</label>
                    <select name="oc-content-parent-id[]" id="oc-content-parent-id" class="oc-form-control-select" multiple="multiple">
                        <?php foreach($courses as $course) :?>
                            <option value="<?php echo $course->ID?>"><?php echo $course->ID . " - " . $course->post_title?></option>
                        <?php endforeach;?>
                    </select>
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
        let options = <?php echo $option?>

             console.log(options);

        $('#oc-content-parent-id').select2();
    });
</script>

