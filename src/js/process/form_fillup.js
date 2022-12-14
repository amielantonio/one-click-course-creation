/*
 * Add javascript and jquery functions here
 */
import {
    instantiateDatePicker,
    dripData,
    adjustDripData,
    $_checker, // variable for checking the loop
} from "../dripdates/dripdates";

/*
 * Require packages from node modules
 */
var moment = require('moment');

let $_keywordsMatch = [];
let $_selectionData = [];
let $_dates = [];

export default function form_fillup() {
    onChangeCourseSelection();
}

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

            let checked = item['comment_status'] === "open" ? "checked='checked'" : "";
            let checkedval = item['comment_status'] === "open" ? "open" : "close";

            table.find('tbody').append('<tr>' +
                '<td>' +
                '<input type="hidden" name="lesson-id[]" class="oc-form-control module-id" value="' + item['lesson-id'] + '">' +
                '<input type="text" name="lesson-name[]" class="oc-form-control module-name" id="module-title-' + index + '" value="' + item['lesson-title'] + '" autocomplete="off">' +
                '</td>' +
                '<td><input type="text" name="topic-date[]" class="oc-form-control module-date-picker" id="start-' + index + '" autocomplete="off" data-index="' + index + '"></td>' +
                '<td class="_text-center">' +
                '<input type="checkbox" name="use-existing[]" class="cb-use-existing" id="existing-' + index + '" value="false">' +
                '<input type="hidden" value="" name="use-existing-val[]" class="txt-use-existing" id="val-use-existing-' + index + '">' +
                '</td>' +
                '<td class="_text-center">' +
                '<input type="checkbox" name="allow-comments[]" class="allow-comments" id="allow-comments-' + index + '" ' + checked + '>' +
                '<input type="hidden" name="allow-comments-val[]" class="txt-allow-comments" id="val-allow-comments-' + index + '" value="' + checkedval + '">' +
                '</td>' +
                '</tr>');

        });
        //add values to hidden fields in the checkbox
        onClickTemplate();
        $courseTitle.val($('#option-' + selectionID).data('course-title'));
        settingsFill(courseMeta);

        //See drip process below;
        instantiateDatePicker();

        $_dates = dripData(selectionData, excludedKeywords, $('#day-interval').val());

        // Apply the $_dates to the datepicker
        applyDatesToPicker();

        // Add the onSelect event listener to the datepicker
        addOnSelectToPicker();

        //
        applyIntervalButton(selectionData);

        //Add values to global variables
        $_keywordsMatch = excludedKeywords;
        $_selectionData = selectionData;
    });


    if( $('#_method').length > 0 ) {

        const select = document.getElementById('course-content');
        const lessons = select.options[select.selectedIndex].dataset.lessons;
        const selectionData = JSON.parse(lessons);
        const courseMeta = select.options[select.selectedIndex].dataset.courseMeta;
        let excludedKeywords = JSON.parse(courseMeta)['excluded_keywords'];

        $_keywordsMatch = excludedKeywords;
        $_selectionData = selectionData;
        $_dates = dripData($_selectionData, excludedKeywords, $('#day-interval').val());

        applyIntervalButton(selectionData);


        // Add the onSelect event listener to the datepicker
        // addOnSelectToPicker();

    }

}

/**
 * Button apply interval
 *
 * @param data
 */
function applyIntervalButton(data) {

    let btnApplyInterval = $('#btn-apply-interval');

    btnApplyInterval.on('click', function () {

        let startDate = $('.start-date-interval').datepicker().data('datepicker');
        let dayInterval = $('#day-interval').val();

        $_dates = dripData(data, $_keywordsMatch, dayInterval, startDate.selectedDates[0]);

        //Apply the global variable $_dates' dates to the date picker
        applyDatesToPicker();

    });
}

/**
 * bind value to hidden to the checkboxes
 */
function onClickTemplate() {
    $('.cb-use-existing').on('click', function () {

        if ($(this).prop("checked") == true) {
            console.log('trez');

            $(this).siblings('[type="hidden"]').val('true');

            let parent = $(this).parent().parent();

            let input = parent.find('input');

            let parentIndex = parent.index();

            input.each((index, element) => {

                const select = document.getElementById('course-content');
                const lessons = select.options[select.selectedIndex].dataset.lessons;
                const jsonVal = JSON.parse(lessons);
                const defaultValue = jsonVal[parentIndex];

                if (element.type !== 'hidden' && element.name !== 'use-existing[]' && element.name !== "topic-date[]") {
                    $('#' + element.id).addClass('_readonly');
                    $('#' + element.id).attr('readonly', 'readonly');
                }

                if (element.name === "allow-comments[]") {
                    element.checked = false;
                    element.disabled = true;
                }

                if (element.name === "allow-comments-val[]") {
                    element.value = "open"
                }

                if (element.name === "lesson-name[]") {
                    element.value = defaultValue['lesson-title'];
                }

            });

        } else if ($(this).prop("checked") == false) {

            console.log('falsez');
            $(this).siblings('[type="hidden"]').val('false');

            let parent = $(this).parent().parent();

            let input = parent.find('input');

            let parentIndex = parent.index();

            input.each((index, element) => {

                const select = document.getElementById('course-content');
                const lessons = select.options[select.selectedIndex].dataset.lessons;
                const jsonVal = JSON.parse(lessons);
                const defaultValue = jsonVal[parentIndex];

                if (element.type !== 'hidden' && element.name !== 'use-existing[]') {
                    $('#' + element.id).removeClass('_readonly');
                    $('#' + element.id).removeAttr('readonly');
                }
                if (element.name === "allow-comments[]") {
                    element.disabled = false;
                }
            });
        }
    });


    $('.allow-comments').on('click', function () {
        if ($(this).prop("checked") == true) {
            $(this).siblings('[type="hidden"]').val('open');
        } else if ($(this).prop("checked") == false) {
            $(this).siblings('[type="hidden"]').val('close');
        }
    });
}

/**
 * Add dates to the date picker, the data came from the global $_dates which
 */
function applyDatesToPicker() {

    $_dates.forEach((item, index) => {

        $('#start-' + index).datepicker().data('datepicker').selectDate(item['date']);

    });

}

/**
 * Add onSelect event to the date picker.
 */
function addOnSelectToPicker() {

    $('.module-date-picker').change((e) => {

        let identifier = $(e.target);

        let date = $(identifier).datepicker().data('datepicker');

        let index = $(identifier).data('index');
        let dayInterval = $('#day-interval').val();

        console.log($_checker);

        if ($_checker) {
            $_dates = adjustDripData(date.selectedDates[0], index, dayInterval);
            console.log($_dates);

            applyDatesToPicker();
        }
    })
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
    let ddTags = $('#oc-tag-id');
    let ddCertificate = $('#oc-course-cert');

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

    if (data['tag_ids'][0] !== "") {
        var tags = data['tag_ids'][0];

        tags = tags.split(",");

        tags.forEach(function (val, index) {
            $('#oc-tag-id').val(tags);
        });

        $('#oc-tag-id').trigger("change");

    }

    if (data['certificate'] !== "") {
        var cert = data['certificate'];

        $('#oc-course-cert').val(cert);

        $('#oc-course-cert').trigger("change");
    }
}
