<?php


if (! function_exists('_view')) {
    /**
     *
     *
     *
     * @param null $view
     * @param array $data
     * @return mixed|string
     */
    function _view($view = null, $data = []) {

        $view = new AWC\Helpers\View($view, $data);

        return $view->render();

    }
}


if (! function_exists('_route')) {
    /**
     *
     *
     * @param $name
     * @param array $parameters
     * @return string
     */
    function _route( $name, $parameters = []) {
        return "admin.php?page={$_GET['page']}&route={$name}";
    }
}

if (! function_exists('_redirect')) {

    /**
     * Redirect to new location
     *
     * @param $to
     * @param array $data
     * @param int $status
     */
    function _redirect( $to, $data = [], $status = 302 ) {


        echo Router::redirect($to, $data, $status);

    }

}

if (! function_exists('_resource_path')) {


    function _resource_path( $path = "" ) {

    }
}

if (! function_exists('_request')) {


    function _request( $path = "" ) {

    }
}

if (! function_exists('_wp_nonce')) {


    function _wp_nonce(){

    }
}

if (! function_exists('_wp_nonce_field')) {


    function _wp_nonce_field() {

    }
}
