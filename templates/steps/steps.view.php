<h1 class="_m-b--50">Setup Classroom</h1>

<div class="cohort-tabs" data-last-selected="1">
    <?php if(isset($course)) : ?>
        <form action="<?php echo _route('classroom-update')?>" method="post" class="oc-form">
            <input type="hidden" name="post_id" value="<?php echo $_GET['posts'];?>" />
    <?php else : ?>
        <form action="<?php echo _route('classroom-store')?>" method="post" class="oc-form">
    <?php endif; ?>
    
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

        <div class="cohort-step cohort-step5" id="cohort-step5">
            <?php include_once "step5.view.php" ?>
        </div>
    </div>

    <div class="cohort-buttons oc-button-group align-center">
        <?php if(isset($course)) : ?>
            <button id="btn-classroom" class="oc-btn oc-btn--primary" type="submit">Update Classroom</button>
        <?php else : ?>
            <button id="btn-classroom" class="oc-btn oc-btn--primary" type="submit">Create Classroom</button>
        <?php endif; ?>
    </div>
    </form>

</div>
<script type="text/javascript">

    let courseContent = '<?php echo json_encode($courseContent)?>';

    $(document).ready(function () {

        $('.select2').select2();
    });
</script>

<style>
    #wpfooter {
        position: relative !important;
    }
</style>
