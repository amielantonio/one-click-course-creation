$ = jQuery;
var moment = require('moment');
moment().format();

import form_fillup from './process/form_fillup';
import private_or_digestive from './process/behaviours';
import {form_validation} from './form/form_validation';


/**
 *
 */


( function ($){

  $(function() {

    form_fillup();
    private_or_digestive();
    form_validation();


    console.log( 'One Click JS Initialized' );

  });


})(jQuery);


