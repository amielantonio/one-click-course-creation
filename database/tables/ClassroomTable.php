<?php

use AWC\Database\Schema;
use AWC\Database\SQL\Blueprint;

class ClassroomTable {

    /**
     * Run the Migration
     */
    public function up()
    {
        Schema::create( 'one_click_classroom_logs', function( Blueprint $table){

//            $table->increments( 'id' );
//            $table->integer( 'form_id' );
//            $table->string( 'ps_no', 128 );
//            $table->text( 'address' );
//            $table->string( 'options', 128 );
//            $table->timestamps();
//            $table->primary('id');
        });
    }
}
