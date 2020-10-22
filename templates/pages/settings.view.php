<h1 class="_m-b--50">Plugin Settings</h1>

<div class="oc-container">
    <div class="oc-collection-container">

        <div class="container-body">

            <form action="<?= _route('save-settings')?>" method="post">
                <div class="oc-form-group">
                    <label for="oc-content-parent-id">Course content parent ID</label>
                    <select name="oc-content-parent-id" id="oc-content-parent-id" class="oc-form-control-select" multiple="multiple">
                        <option></option>
                    </select>
                </div>

                <div class="cohort-buttons oc-button-group align-center">
                    <button id="btn-classroom" class="oc-btn oc-btn--primary" type="button">Save Settings</button>
                </div>
            </form>

        </div>


    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#oc-content-parent-id').select2();
    });
</script>
