<?php


if (! function_exists('_view')) {



    function _view($view = null, $data = []) {

        $view = new AWC\Helpers\View($view, $data);

        return $view->render();

    }
}


if (! function_exists('')) {

    function _route( $name, $parameters = []) {

    }
}

if (! function_exists('')) {


    function _redirect( $to = null, $status = 302 ) {

    }

}


if (! function_exists('')) {


    function _resource_path( $path = "" ) {

    }
}

if (! function_exists('')) {


    function _request( $path = "" ) {

    }
}

if (! function_exists('')) {


    function _wp_nonce(){

    }
}

if (! function_exists('')) {


    function _wp_nonce_field() {

    }
}
