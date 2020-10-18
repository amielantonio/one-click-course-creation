<h1>Add New Cohort</h1>

<div class="cohort-tabs">
    <ul class="cohort-progress">
        <li><a href="#">Select a Course</a></li>
        <li><a href="#">Module Schedules</a></li>
        <li><a href="#">Course Settings</a></li>
        <li><a href="#">Tags</a></li>
    </ul>

    <div class="cohort-steps">
        <form action="" method="post">
            <div class="cohort-step1" id="cohort-step1">
                <?php include_once "step1.view.php" ?>
            </div>
            <div class="cohort-step2" id="cohort-step2">
                <?php include_once "step2.view.php" ?>
            </div>
            <div class="cohort-step3" id="cohort-step3">
                <?php include_once "step3.view.php" ?>
            </div>
            <div class="cohort-step4" id="cohort-step4">
                <?php include_once "step4.view.php" ?>
            </div>
        </form>
    </div>

    <div class="cohort-buttons oc-button-group align-center justify-between">
        <button id="previous" class="oc-btn oc-btn--primary" type="button">Previous</button>
        <button id="next" class="oc-btn oc-btn--primary" type="button">Next</button>
    </div>

</div>
