import {inArraySubstr} from '../helpers/array';

const $ = jQuery;
const $_moment = require('moment');
let $_dates = [];

/**
 * Instantiate the date picker fields
 */
export const instantiateDatePicker = () => {
  $('.module-date-picker').datepicker({
    language: "en",
    dateFormat: 'dd MM, yyyy',
    timepicker: true,
    todayButton: new Date(),
    autoClose: true
  });
};

/**
 * Create the global drip date that would then be processed that
 * would be added inside the module form of One-click classroom
 *
 * @param lessonData
 * @param excludedKeywords
 * @param dayInterval
 * @returns {Array} - returns the global state data of the drip dates
 */
export const createDripData = (lessonData, excludedKeywords, dayInterval = 7) => {
  let passedDate = "";
  let $date = "";
  let startDate = $_moment();
  let is_startDate = false;
  let has_startDate = false;

  startDate.hour(24);
  startDate.minute(1);

  lessonData.forEach((item, index) => {

    if (!inArraySubstr(item['lesson-title'], excludedKeywords)) {
      passedDate = startDate;

      // Checks whether a start date for the drip has been established,
      // if the start date has been checked confirmed, start adding
      // the dayInterval to the moment() date
      //
      // has_startDate - is an identifier whether a startDate has been found
      // is_startDate  - is a variable to identify whether the array is a startDate or not
      if(has_startDate){
        startDate.add(dayInterval, 'day');
        is_startDate = false;
      } else {
        is_startDate = true;
        has_startDate = true;
      }
    } else {
      passedDate = "";
    }

    $date = (passedDate !== "" ) ? passedDate.toDate() : "";

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

export const adjustDates = (startDate, formData) => {

};


export const setDates = (dates) => {
  $_dates = dates;
};

export const getDates = () => {
  return $_dates;
};


