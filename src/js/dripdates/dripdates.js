import {inArraySubstr} from '../helpers/array';

const $ = jQuery;
const $_moment = require('moment');
let $_dates = [];
let $_formData = [];
let $_excludedKeywords = [];

export let $_checker = true;

/**
 * Instantiate the date picker fields
 */
export const instantiateDatePicker = () => {
    $('.module-date-picker').datepicker({
        language: "en",
        dateFormat: 'dd MM, yyyy',
        timepicker: true,
        todayButton: new Date(),
        autoClose: true,
        onSelect: function(formattedDate, date, inst) {
            $(inst.el).trigger('change');
        },
        onShow: function(dp, animation){
            $_checker = true;
        }
    });
};

/**
 * Create the global drip date that would then be processed that
 * would be added inside the module form of One-click classroom
 *
 * @param lessonData
 * @param excludedKeywords
 * @param dayInterval
 * @param startDate
 * @returns {Array} - returns the global state data of the drip dates
 */
export const dripData = (lessonData, excludedKeywords, dayInterval = 7, startDate = "") => {
    startDate = startDate === "" ? $_moment() : $_moment(startDate);
    $_formData = lessonData;
    $_excludedKeywords = excludedKeywords;

    let passedDate = "";
    let $date = "";
    let is_startDate = false;
    let has_startDate = false;

    $_dates = []; //reset the global $_dates variable

    startDate.hour(24);
    startDate.minute(1);
    startDate.add(-1, 'day'); // subtract 1 day to get the date today, the date was adjusted due to the increase in hrs

    lessonData.forEach((item, index) => {

        if (!inArraySubstr(item['lesson-title'], excludedKeywords)) {
            passedDate = startDate;

            // Checks whether a start date for the drip has been established,
            // if the start date has been checked confirmed, start adding
            // the dayInterval to the moment() date
            //
            // has_startDate - is an identifier whether a startDate has been found
            // is_startDate  - is a variable to identify whether the array is a startDate or not
            if (has_startDate) {
                startDate.add(dayInterval, 'day');
                is_startDate = false;
            } else {
                is_startDate = true;
                has_startDate = true;
            }
        } else {
            passedDate = "";
        }

        $date = (passedDate !== "") ? passedDate.toDate() : "";

        // Push data to $_dates global variable

        $_dates.push({
            lesson_id: item['lesson-id'],
            lesson_title: item['lesson-title'],
            date: $date,
            has_passed: false,
            is_start_date: is_startDate
        })


    });

    return $_dates;
};

export const adjustDripData = (date, index, dayInterval) => {
    date = $_moment(date);

    for( let i = index; i < $_dates.length; i++) {
        if( !inArraySubstr( $_dates[i]['lesson_title'], $_excludedKeywords)) {
            $_dates[i]['date'] = date.toDate(); //push the value of the passed initial date
            $_dates[i]['has_passed'] = true;

            //Start drip process here
            date.add(dayInterval, 'day');
        }
    }

    $_checker = false;
    return $_dates;
};


