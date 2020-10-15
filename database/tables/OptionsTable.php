<?php

use AWC\Database\Schema;
use AWC\Database\SQL\Blueprint;

class OptionsTable {

    /**
     * Run the Migration
     */
    public function up()
    {
        Schema::create( 'strataplan_form_options', function( Blueprint $table){

            $table->increments( 'id' );
            $table->integer( 'form_id' );
            $table->string( 'ps_no', 128 );
            $table->text( 'address' );
            $table->string( 'options', 128 );
            $table->timestamps();
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists( 'strataplan-form-data' );
    }
}
