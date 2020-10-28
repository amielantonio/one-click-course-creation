export default function form_fillup() {
  onChangeCourseSelection();
}


function onChangeCourseSelection() {

  let selection = $('#course-content');

  selection.change(function () {
    let selectionID = $(this).val();
    let table = $('#tbl-module-schedule');
    let $courseTitle = $('#course-title');
    let selectionData = $('#option-' + selectionID).data('lessons');
    let courseMeta = $('#option-' + selectionID).data('course-meta');

    selectionData.forEach(function (item, index) {
      table.find('tbody').append('<tr>' +
        '<td><input type="text" class="oc-form-control module-name" value="' + item + '"></td>' +
        '<td><input type="text" class="oc-form-control module-date-picker" id="start-' + index + '"></td>' +
        '<td><input type="text" class="oc-form-control module-date-picker" id="end-' + index + '"></td>' +
        '</tr>')
    });

    $courseTitle.val($('#option-' + selectionID).data('course-title'));

    $('.module-date-picker').datepicker({
      language: "en",
      dateFormat: 'dd MM, yyyy',
      timepicker: true
    });

    datePicker(selectionData);
    settingsFill(courseMeta);

  });
}

function datePicker(data) {
  // let datePickers = $('.module-date-picker').datepicker().data('datepicker').selectDate(new Date());
  var startDate = moment();
  startDate.hour(24);
  startDate.minute(1);

  var endDate = moment();
  endDate.hour(23);
  endDate.minute(59);

  var dayInterval = $('#day-interval').val();
  var endDay = dayInterval - 1;

  data.forEach(function (item, index) {

    $('#start-' + index).datepicker().data('datepicker').selectDate(startDate.toDate());

    endDate.add(endDay, 'day');

    $('#end-' + index).datepicker().data('datepicker').selectDate(endDate.toDate());

    startDate.add(dayInterval,'day');

  });

}

function settingsFill(data) {
  let cbActiveCourse = $('#awc_active_course');
  let cbCollapseReplies = $('#collapse_replies_for_course');
  let cbDailyDigests = $('#email_daily_comment_digest');
  let cbPrivateComments = $('#awc_private_comments');
  // let cbExcerpt = $('#excerpt');


  if (data['awc_active_course'] !== "" || data['awc_active_course']) {
    cbActiveCourse.prop('checked', true);
  } else {
    cbActiveCourse.prop('checked', false);
  }

  if (data['collapse_replies_for_course'] !== "") {
    cbCollapseReplies.prop('checked', true);
  } else {
    cbCollapseReplies.prop('checked', false);
  }

  if (data['email_daily_comment_digest'] !== "") {
    cbDailyDigests.prop('checked', true);
  } else {
    cbDailyDigests.prop('checked', false)
  }

  if (data['awc_private_comments'] !== "") {
    cbPrivateComments.prop('checked', true);
  } else {
    cbPrivateComments.prop('checked', false);
  }

}
