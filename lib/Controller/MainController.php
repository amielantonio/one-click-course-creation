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
        global $wpdb;

        $posts = new Posts;
        $courses = $posts->select('*')->where('post_type','sfwd-courses');


        // Memberium data list
        $memberium = get_option('memberium', []);

        // GET THE TAG LIST
        $tags = [];
        $table = 'memberium_tags';
        $appname = "lf159"; # memberium_tags table  appname field
        $sql = "SELECT id, name FROM {$table} WHERE `appname` = '{$appname}' ORDER BY category, name ";
        $result = $wpdb->get_results($sql, ARRAY_A);
        foreach ($result as $data) {
            $tags['mc'][$data['id']] = $data['name'];
            //$tags['lc'][$data['id']] = strtolower($data['name']);
        }
        
        $tags = $tags['mc'];
        // INCLUDE TAG ON LIST
        foreach ($memberium['memberships'] as $key => $data) {       
            $tag = !empty($tags[$key]) ? $tags[$key]." ({$key})" :  '(Missing Tag)';
            $memberium['memberships'][$key]['tag_name']  =  $tag;
        }


        return (new View('steps/steps'))
                ->with('memberships',$memberium['memberships'])
                ->render();
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

    

    // public function m_tag()
    // {
    //     global $wpdb;
        
    //     $this->dd($a);

    //     return (new View('pages/m_tag'))->with('data',$my_query)->render();
    // }



}
