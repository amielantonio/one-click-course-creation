<h3 class="cohort-title">Module Schedule</h3>

<div class="oc-form-group form-row justify-right">
    <div id="start-date-container" style="flex-basis:25%;display: flex;align-items: center;" class="_m-r--20">
        <label for="start-date" style="width: 70%;">Start Date Interval: </label>
        <input type="text" name="start-date-for-interval" class="start-date-interval _m-r--10">
    </div>
    <div id="day-interval-container" style="flex-basis:25%;display: flex;align-items: center;">
        <label for="day-interval" style="width: 55%;">Day interval: </label>
        <input type="number" name="day-interval" class="_text-right _m-r--10 _m-l--10" id="day-interval" value="7"><button type="button" id="btn-apply-interval" class="oc-btn oc-btn--primary oc-btn--small">Apply</button>
    </div>
</div>

<table class="oc-table oc-table-striped" cellpadding="0" cellspacing="0" id="tbl-module-schedule">
    <thead>
    <tr>
        <th style="width: 55%;">Module</th>
        <th>Start</th>
        <th>Use Template</th>
    </tr>
    </thead>
    <tbody>

    </tbody>
    <tfoot>
    <tr>
        <th>Module</th>
        <th>Start</th>
        <th>Use Template</th>
    </tr>
    </tfoot>
</table>
<script>

    $('.start-date-interval').datepicker({
        language: "en",
        dateFormat: 'dd MM, yyyy',
        todayButton: new Date(),
        autoClose: false,
        startDate: new Date()
    });

    $('.start-date-interval').datepicker().data('datepicker').selectDate(new Date());

</script>