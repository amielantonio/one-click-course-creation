$ = jQuery;
var moment = require('moment');
moment().format();

import form_fillup from './process/form_fillup';
import private_or_digestive from './process/behaviours';


/**
 *
 */


( function ($){

  $(function() {

    form_fillup();
    private_or_digestive();


    console.log( 'App Initialized' );

  });


})(jQuery);


