export default function form_fillup() {
  onChangeCourseSelection();
}

let theArray = [];

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

    selectionData.forEach(function (item, index) {
      table.find('tbody').append('<tr>' +
        '<td><input type="text" class="oc-form-control module-name" id="module-title-'+index+'" value="' + item + '"></td>' +
        '<td><input type="text" class="oc-form-control module-date-picker" id="start-' + index + '"></td>' +
        '<td><input type="text" class="oc-form-control module-date-picker" id="end-' + index + '"></td>' +
        '</tr>')
    });

    $courseTitle.val($('#option-' + selectionID).data('course-title'));

    $('.module-date-picker').datepicker({
      language: "en",
      dateFormat: 'dd MM, yyyy',
      timepicker: true,
      todayButton: new Date(),
      autoClose: true
    });

    datePicker(selectionData);
    settingsFill(courseMeta);

  });
}

/**
 *
 *
 * @param data
 */
function datePicker(data) {

  var startDate = moment();
  startDate.hour(24);
  startDate.minute(1);
  startDate.add(-1, 'day');

  var dayInterval = $('#day-interval').val();

  data.forEach(function (item, index) {

    if( $('#module-title-' + index).val() !== "Welcome" && !$('#module-title-' + index).val().includes('Course chat')) {

      $('#start-' + index).datepicker().data('datepicker').selectDate(startDate.toDate());

      startDate.add(dayInterval, 'day');

      var endDate = startDate.clone();
      endDate.hour(23);
      endDate.minute(59);

      endDate.add(-1, 'day');

      console.log(index + ": initialize...");

      $('#end-' + index).datepicker().data('datepicker').selectDate(endDate.toDate());

      $('#start-' + index).datepicker().data('datepicker').update('onSelect', function(fd, d, inst){
        if(!theArray.includes(index + "-start")) {

          console.log(index + "-start");

          theArray.push(index + "-start");
          dripDatePicker( d, index, data, 'start' );
        }
      });

      $('#end-' + index).datepicker().data('datepicker').update('onSelect', function(fd, d, inst){
        if(!theArray.includes(index + "-end")) {

          console.log(index + "-end");

          theArray.push(index + "-end");
          dripDatePicker(d, index, data, 'end');
        }
      });
    }

  });

}


function dripDatePicker( currentDate, dateIndex, dateData, pickerType = null ) {

  var startDate = moment(currentDate);
  startDate.hour(24);
  startDate.minute(1);
  startDate.add(-1, 'day');

  var dayInterval = $('#day-interval').val();

  // If date picker type is not null, then process them first before going inside the loop
  if( pickerType !== null ) {
    if(pickerType == 'start'){
      var endDateDefault = startDate.clone();
      endDateDefault.hour(23);
      endDateDefault.minute(59);

      endDateDefault.add((dayInterval - 1), 'day');

      $('#end-' + dateIndex).datepicker().data('datepicker').selectDate(endDateDefault.toDate());
      dateIndex = dateIndex + 1;
    }

    if( pickerType == 'end') {
      dateIndex = dateIndex + 1;
    }
  }

  //Loop in the date indexes to add the selection
  for( var _x = dateIndex; _x < dateData.length; _x++ ) {

    console.log(_x);

    startDate.add(dayInterval, 'day');

    $('#start-' + _x).datepicker().data('datepicker').selectDate(startDate.toDate());

    var endDate = startDate.clone();
    endDate.hour(23);
    endDate.minute(59);

    endDate.add((dayInterval - 1), 'day');

    $('#end-' + _x).datepicker().data('datepicker').selectDate(endDate.toDate());
  }

  console.log(_x);
  console.log('test');

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
