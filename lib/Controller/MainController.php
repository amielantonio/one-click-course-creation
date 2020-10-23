<?php
namespace AWC\Controller;

use AWC\Helpers\Func;
use AWC\Helpers\View;
use AWC\Core\Request;
use AWC\Core\CoreController;
use AWC\Model\AccessDevice;
use WP_Query;
use AWC\Model\Posts;

class MainController extends CoreController{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Landing Page
     *
     * @return mixed
     * @throws \exception
     */
    public function index()
    {

        return (new View('dashboard/dashboard'))->render();
    }

    /**
     * Create entry
     *
     * @return mixed|string
     * @throws \Exception
     */
    public function create()
    {

        $posts = new Posts;
        $courses = $posts->select('*')->where('post_type','sfwd-courses');

        return (new View('steps/steps'))->render();
    }


    public function test(Request $request)
    {
        var_dump($request);
    }

    public function update()
    {

        $args = [
                    'p' => 88724, // GET the ID of the edit post
                    'post_type' => 'sfwd-courses',
                ];
        $my_query = new WP_Query($args);



        return (new View('pages/update'))->with('data',$my_query)->render();
    }

    private static function sku_tags()
    {
        if (MEMBERIUM_SKU == 'm4is') {
            $tag = array( 
                'access_tags' => '_is4wp_access_tags', 
                'access_tags2' => '_is4wp_access_tags2', 
                'anonymous_only' => '_is4wp_anonymous_only', 
                'any_loggedin_user' => '_is4wp_any_loggedin_user', 
                'any_membership' => '_is4wp_any_membership', 
                'can_comment' => '_is4wp_can_comment', 
                'commenter_action' => '_is4wp_commenter_action', 
                'commenter_goal' => '_is4wp_commenter_goal', 
                'commenter_tag' => '_is4wp_commenter_tag', 
                'contact_ids' => '_is4wp_contact_ids', 
                'custom_code' => '_iswp_custom_code', 
                'discourage_cache' => '_is4wp_discourage_cache', 
                'facebook_crawler' => '_is4wp_facebook_crawler', 
                'force_public' => '_is4wp_force_public', 
                'google_1st_click' => '_is4wp_google_1stclick', 
                'hide_from_menu' => '_is4wp_hide_from_menu', 
                'memberships' => '_is4wp_membership_levels', 
                'private_comments' => '_is4wp_private_comments', 
                'prohibited_action' => '_is4wp_prohibited_action', 
                'redirect_url' => '_is4wp_redirect_url',
            );
        } elseif (MEMBERIUM_SKU == 'm4ac') {
            $tag = array();
        }
        return $tag;
    }

    public function idk($post_id = 0, $membership = [], $idk_know = null)
    {
        if (empty($membership) || empty($post_id)) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (is_string($membership)) {
            $membership = array($membership => $idk_know,);
        }
        $arr = array(
            'any_loggedin_user', 
            'any_membership', 
            'facebook_crawler', 
            'google_1st_click', 
            'hide_completely', 
            'hide_from_menu', 
            'private_comments',
        );
        $tag = self::sku_tags();
        foreach ($membership as $key => $idk_know) {
            if (array_key_exists($key, $tag)) {
                $idk_know = array_key_exists($key, $arr) ? (int) (bool) trim($idk_know) : $idk_know;
                $idk_know = is_string($idk_know) ? trim($idk_know) : $idk_know;
                add_post_meta($post_id, $tag[$key], $idk_know, true) or update_post_meta($post_id, $tag[$key], $idk_know);
            } else {
            }
        }
    }

    public function m_tag()
    {
         global $wpdb;
        //wplght_eu

         

    
        $vwplzo10 = "SELECT `post_id`, `meta_value` FROM {$wpdb->postmeta} WHERE `meta_key` = '_is4wp_membership_levels' AND `meta_value` > '' ";
        $vwplfc_69 = $wpdb->get_results($vwplzo10, ARRAY_A);
        foreach ($vwplfc_69 as $vwplz7loh) {
            // $a = get_post_meta($vwplz7loh['post_id'], '_is4wp_any_membership', true);
        //     $vwplr3sp = explode(',', $vwplz7loh['meta_value']);
        
             echo "<pre>";
             print_r($vwplz7loh);


        //     //$vwplbhklgq = implode(',', array_diff($vwplr3sp, $vwplqi8h));
        //     //$this->idk($vwplz7loh['post_id'], 'memberships', $vwplbhklgq);
        }

        
        $args = [
                    'p' => 88724, // GET the ID of the edit post
                    'post_type' => 'sfwd-courses',
                ];
         $my_query = new WP_Query($args);



        // global $wpdb, $wp_roles;
        // $vwplb5w_go = empty($this->settings['memberships']) ? [] : $this->settings['memberships'];
        // $vwplvk0tz = $wp_roles->roles;
        // $vwplupjl = [];
        // foreach ($vwplvk0tz as $vwpl_rj3 => $vwplgp38av) {
        //     if ($vwpl_rj3 <> 'administrator') {
        //         $vwplupjl[] = array('id' => $vwpl_rj3, 'name' => $vwplgp38av['name']);
        //     }
        // }
        // unset($vwplvk0tz, $vwpl_rj3, $vwplgp38av);
        // $vwplbhklgq = [];
        // foreach ($vwplb5w_go as $vwplr85wt => $vwplz67za) {
        //     if ((int) $vwplr85wt > 0 && (int) $vwplz67za['main_id'] == $vwplr85wt) {
        //         $vwplz67za['login_redirect_priority'] = isset($vwplz67za['login_redirect_priority']) ? $vwplz67za['login_redirect_priority'] : 0;
        //         $vwplbhklgq[$vwplr85wt] = $vwplz67za;
        //     }
        // }
        // $vwplb5w_go = $vwplbhklgq;
        // echo "<pre>";
        // $vwplc4fu20 = wplr97_::wplwcryp(false);
        // $vwplc4fu20 = $vwplc4fu20['mc'];

        // foreach ($vwplb5w_go as $vwplr85wt => $vwpll53st) {

        //     print_r($vwplb5w_go[$vwplr85wt]);
        //     //print_r($vwplc4fu20[$vwplr85wt]);
        // }

        // die();



        return (new View('pages/m_tag'))->with('data',$my_query)->render();
    }


}
