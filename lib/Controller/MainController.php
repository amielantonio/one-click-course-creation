<?php
namespace AWC\Controller;

use AWC\Helpers\Func;
use AWC\Helpers\View;
use AWC\Core\Request;
use AWC\Core\CoreController;
use AWC\Model\AccessDevice;
use AWC\Model\PostMeta;
use AWC\Traits\CourseMeta;
use WP_Query;
use AWC\Model\Posts;
use Exception;

class MainController extends CoreController
{

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

        global $wpdb;
      
        $author = isset($_GET['author']) ? $_GET['author'] : "";

        $args = array(
            'posts_per_page'=> -1,
            'post_type' => 'sfwd-courses',
            'meta_query' => array(
                array(
                    'key'     => 'created-from-one-click',
                    'value'   => true,
                    'compare' => '='
                )
            ),
            'post_status' => 'any',
            'orderby' => 'date_created',
            'order' => 'DESC'
        );

        if($author <> "" ) {
            $args['author'] = $author;
        }

        $posts = get_posts( $args );

        foreach( $posts as $post ) :
            $courseSelected = get_post($post->ID);
            $courseContent[$courseSelected->ID]['course_slug'] = $courseSelected->post_name;
            $courseContent[$courseSelected->ID]['course_name'] = $courseSelected->post_title;
            $courseContent[$courseSelected->ID]['author'] = [
                'id' => $courseSelected->post_author,
                'name' => get_user_by('id', $courseSelected->post_author)->data->display_name
            ];
            $courseContent[$courseSelected->ID]['post_status'] = $courseSelected->post_status;
            $courseContent[$courseSelected->ID]['date_created'] = date("F j, Y, g:i a", strtotime($courseSelected->post_date));
            
            $tag_ids = implode(', ',get_post_meta($post->ID, '_is4wp_access_tags'));
            $sql = "SELECT id FROM `memberium_tags` WHERE id IN($tag_ids)";
            $courseContent[$courseSelected->ID]['tag_info'] = $wpdb->get_results($sql, ARRAY_A);
            wp_reset_postdata(); 
        endforeach;

        
        return (new View('dashboard/dashboard'))
                ->with('courseContent', $courseContent )
                ->render();
    }

}
