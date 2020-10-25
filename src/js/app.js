$ = jQuery;
import tabs from './components/tabs';
import form_fillup from './process/form_fillup';
var moment = require('moment');
moment().format();

/**
 *
 */


( function ($){

  $(function() {

    form_fillup();

    console.log( 'App Initialized' );

  });


})(jQuery);


