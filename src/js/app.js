var moment = require('moment');
moment().format();

import form_fillup from './process/form_fillup';


/**
 *
 */


( function ($){

  $(function() {

    form_fillup();

    console.log( 'App Initialized' );

  });


})(jQuery);


