<?php


if (! function_exists('_view')) {
    /**
     * Get view function
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

if (! function_exists('_channel')) {

    /**
     * Add link url for channel routes
     *
     * @param $name
     * @param array $parameters
     * @param $page
     * @return string
     */
    function _channel( $name, $parameters = [], $page = "") {

        $page = $page <> ""
            ? $page
            : $_GET['page'];

        $link = "admin.php?page={$page}&route={$name}";

        if( !empty($parameters)) {
            foreach($parameters as $key => $value) {
                $link .= "&{$key}=$value";
            }
        }

        return $link;
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


        if(headers_sent())
        {
            $string = '<script type="text/javascript">';
            $string .= 'window.location = "' . $to . '"';
            $string .= '</script>';

            echo $string;
        }
        else
        {
            if (isset($_SERVER['HTTP_REFERER']) AND ($to == $_SERVER['HTTP_REFERER']))
                header('Location: '.$_SERVER['HTTP_REFERER']);
            else
                header('Location: '.$to);

        }
        exit;

    }

}

if (! function_exists('_resource_path')) {


    /**
     * Get resource path
     *
     * @param string $path
     */
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
