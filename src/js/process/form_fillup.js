import {inArray, inArraySubstr} from '../helpers/array';

var moment = require('moment');

export default function form_fillup() {
  onChangeCourseSelection();
}

let $_keywordsMatch = [];
let $_selectionData = [];
let $_dripdates = [];
let $_initialDate = "";

/**
 * on Change Course selection
 */
function onChangeCourseSelection() {

  let selection = $('#course-content');

  selection.change(function () {
    let selectionID = $(this).val();
    let table = $('#tbl-module-schedule');
    let $courseTitle = $('#course-title');
    let selectionData = $('#option-' + selectionID).data('lessons');
    let courseMeta = $('#option-' + selectionID).data('course-meta');
    let excludedKeywords = courseMeta.excluded_keywords;

    table.find('tbody tr').remove();

    selectionData.forEach(function (item, index) {
      table.find('tbody').append('<tr>' +
        '<td><input type="text" class="oc-form-control module-name" id="module-title-' + index + '" value="' + item + '"></td>' +
        '<td><input type="text" class="oc-form-control module-date-picker" id="start-' + index + '"></td>' +
        '</tr>');
    });

    $courseTitle.val($('#option-' + selectionID).data('course-title'));

    $('.module-date-picker').datepicker({
      language: "en",
      dateFormat: 'dd MM, yyyy',
      timepicker: true,
      todayButton: new Date(),
      autoClose: true
    });

    datePicker(selectionData, excludedKeywords);
    settingsFill(courseMeta);
    applyIntervalButton(selectionData);

    $_keywordsMatch = excludedKeywords;
    $_selectionData = selectionData;


  });
}

/**
 * Button apply interval
 *
 * @param data
 */
function applyIntervalButton(data) {

  let btnApplyInterval = $('#btn-apply-interval');

  btnApplyInterval.on('click', function () {
    dripDatePicker(null, 0, data);
  });


}

/**
 * Initial Drip behaviour and adding of onSelect property for air-datepicker modules.
 *
 * @param data
 * @param excludedKeywords
 */
function datePicker(data, excludedKeywords) {

  var startDate = moment();
  startDate.hour(24);
  startDate.minute(1);
  startDate.add(-1, 'day');

  var dayInterval = $('#day-interval').val();


  //Loop thru all data to add the value inside the air-datepicker.
  data.forEach(function (item, index) {

    if (!inArraySubstr($('#module-title-' + index).val(), excludedKeywords)) {

      $('#start-' + index).datepicker().data('datepicker').selectDate(startDate.toDate());

      startDate.add(dayInterval, 'day');

      $('#start-' + index).datepicker().data('datepicker').update('onSelect', function (fd, d, inst) {
        // Do nothing if selection was cleared
        if (!d) return;

        dripDatePicker(d, index, data, 'start');
      });
    }

    $_dripdates.push($('#start-' + index).val());
  });
}

/**
 * Drip Date picker process
 *
 * @param currentDate
 * @param dateIndex
 * @param dateData
 * @param pickerType
 */
function dripDatePicker(currentDate = null, dateIndex, dateData, pickerType = null) {

  var startDate;
  var startDateChecker;

  if (currentDate == null) {
    startDate = moment();
  } else {
    startDate = moment(currentDate);
  }

  startDate.hour(24);
  startDate.minute(1);
  startDate.add(-1, 'day');

  var dayInterval = $('#day-interval').val();

  // If date picker type is not null, then process them first before going inside the loop
  if (pickerType !== null) {
    if (pickerType == 'start') {
      dateIndex = dateIndex + 1;
    }
  }

  //Loop in the date indexes to add the selection
  for( var _x = dateIndex; _x < $_dripdates.length; _x++ ) {

    if(! inArraySubstr($('#module-title-' + _x).val(), $_keywordsMatch) ) {

      console.log( "enter: " + $('#module-title-' + _x).val() );

      startDate.add(dayInterval, 'day');

      $('#start-' + _x).datepicker().data('datepicker').selectDate(startDate.toDate());
    }
  }

  // var prevValue = "";
  // var initialDate = "";
  //
  // $_dripdates.forEach(function (value, index) {
  //
  //   if (prevValue == "" && value != "") {
  //     initialDate = value;
  //   }
  //
  //   if (initialDate != "") {
  //     startDate = moment(initialDate, 'DD MMMM, YYYY hh:mm a');
  //
  //     console.log(startDate.toDate());
  //
  //     startDate.add(dayInterval, 'day');
  //
  //     $('#start-' + index).datepicker().data('datepicker').selectDate(startDate.toDate());
  //
  //   }
  //
  //   initialDate = "";
  //
  //   prevValue = value;
  //
  //
  // });
}

/**
 * Fill up checkboxes of the steps
 *
 * @param data
 */
function settingsFill(data) {
  let cbActiveCourse = $('#awc_active_course');
  let cbCollapseReplies = $('#collapse_replies_for_course');
  let cbDailyDigests = $('#email_daily_comment_digest');
  let cbPrivateComments = $('#awc_private_comments');
  let ccRecipients = $('#cc_recipients');

  if (data['awc_active_course'] == 1) {
    cbActiveCourse.prop('checked', true);
  } else {
    cbActiveCourse.prop('checked', false);
  }

  if (data['collapse_replies_for_course'] != "") {
    cbCollapseReplies.prop('checked', true);
  } else {
    cbCollapseReplies.prop('checked', false);
  }

  if (data['email_daily_comment_digest'] == 1) {
    cbDailyDigests.prop('checked', true);
  } else {
    cbDailyDigests.prop('checked', false)
  }

  if (data['awc_private_comments'] != "") {
    cbPrivateComments.prop('checked', true);
  } else {
    cbPrivateComments.prop('checked', false);
  }

  if (data['cc_recipients'][0] !== "") {
    ccRecipients.val(data['cc_recipients']);
  } else {
    ccRecipients.val();
  }
}
