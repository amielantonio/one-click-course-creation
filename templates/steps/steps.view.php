<h1 class="_m-b--50">Setup Classroom</h1>

<div class="cohort-tabs" data-last-selected="1">
    <form action="admin.php<?php echo _route('classroom-store')?>" method="post" class="oc-form">

    <div class="cohort-steps">
            <div class="cohort-step cohort-step1" id="cohort-step1">
                <?php include_once "step1.view.php" ?>
            </div>
            <div class="cohort-step cohort-step2" id="cohort-step2">
                <?php include_once "step2.view.php" ?>
            </div>
            <div class="cohort-step cohort-step3" id="cohort-step3">
                <?php include_once "step3.view.php" ?>
            </div>
            <div class="cohort-step cohort-step4" id="cohort-step4">
                <?php include_once "step4.view.php" ?>
            </div>
    </div>

    <div class="cohort-buttons oc-button-group align-center">
        <button id="btn-classroom" class="oc-btn oc-btn--primary" type="submit">Create Classroom</button>
    </div>
    </form>

</div>
<script type="text/javascript">

    let courseContent = '<?php echo json_encode($courseContent)?>';

    $(document).ready(function () {

        $('.select2').select2();
    });
</script>
