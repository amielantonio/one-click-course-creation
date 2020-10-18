<h1>Setup Classroom</h1>

<div class="cohort-tabs" data-last-selected="1">
    <ul class="cohort-progress">
        <li><a data-target="#cohort-step1">Select a Course</a></li>
        <li><a data-target="#cohort-step2">Module Schedules</a></li>
        <li><a data-target="#cohort-step3">Course Settings</a></li>
        <li><a data-target="#cohort-step4">Tags</a></li>
    </ul>

    <div class="cohort-steps">
        <form action="" method="post">
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
        </form>
    </div>

    <div class="cohort-buttons oc-button-group align-center">
        <button id="btn-previous" class="oc-btn oc-btn--primary" type="button">Previous</button>
        <button id="btn-next" class="oc-btn oc-btn--primary" type="button">Next</button>
    </div>

</div>
