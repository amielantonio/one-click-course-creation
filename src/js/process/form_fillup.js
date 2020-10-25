export default function form_fillup() {
    onChangeCourseSelection();
}


function onChangeCourseSelection(){

    let selection = $('#course-content');

    selection.change(function(){
        let selectionID = $(this).val();
        let table = $('#tbl-module-schedule');
        let selectionData = $('#option-' + selectionID).data('lessons');

        selectionData.forEach(function(item, index){
            table.find('tbody').append('<tr>' +
                '<td>'+ item +'</td>' +
                '<td><input type="text" class="oc-form-control module-date-picker" id="start-'+index+'"></td>' +
                '<td><input type="text" class="oc-form-control module-date-picker" id="end-'+index+'"></td>' +
                '</tr>')
        });

        $('.module-date-picker').datepicker({
            language: "en",
            dateFormat: 'dd MM, yyyy',
            timepicker: true
        });

        datePicker(selectionData);

    });
}

function datePicker( data ) {
    // let datePickers = $('.module-date-picker').datepicker().data('datepicker').selectDate(new Date());
    var selectedDate = moment();

    data.forEach(function(item, index){


        var toDate = selectedDate;

        $('#start-' + index).datepicker().data('datepicker').selectDate(toDate.toDate());

        selectedDate.add('6', 'days');


        $('#end-'+index).datepicker().data('datepicker').selectDate(toDate.toDate());

        selectedDate.add('6', 'days');


    });

}
