export default function tabs() {
    progress();
    steps();
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

    // steps.addClass('hide');
    // steps.eq(currentStep).removeClass('hide');


}
