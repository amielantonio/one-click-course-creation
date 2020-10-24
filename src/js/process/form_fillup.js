export default function form_fillup() {
    onChangeCourseSelection();
}


function onChangeCourseSelection(){

    let selection = $('#course-content');

    selection.change(function(){
        console.log($(this).val());
    });
}

