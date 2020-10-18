export default function tabs() {
    progress();
    steps();
    buttons();
}

function progress() {
    let progressItem = $('.cohort-progress a');

    //Defaults
    progressItem.addClass('disabled');
    progressItem.eq(0).removeClass('disabled').addClass('active');


    progressItem.on('click', function (e) {
        e.preventDefault();


        //Progress behaviour
        progressItem.removeClass('active');
        $(this).removeClass('disabled').addClass('active');


    });
}

function steps( $step = 1 ) {
    let steps = $('.cohort-step');

    let currentStep = $step - 1;

    steps.addClass('hide');
    steps.eq(currentStep).removeClass('hide');


}


function buttons() {
    let lastSelected = $('.cohort-tabs').data('last-selected');
    let prev = $('#btn-previous');
    let next = $('#btn-next');


    if( lastSelected === 1  ) {
        prev.hide();
    }

    if( lastSelected === $('.cohort-step').length ) {
        next.hide();
    }
}