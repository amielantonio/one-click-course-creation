export default function tabs() {
    progress();
    steps();
}

function progress() {
    let progressItem = $('.cohort-progress a');

    progressItem.on('click', function(e){
        e.preventDefault();

        console.log('test click');
    });
}

function steps() {

}
