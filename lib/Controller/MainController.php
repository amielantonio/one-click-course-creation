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
    use CourseMeta;

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
            'orderby' => 'date_created',
            'order' => 'DESC'
        );

        if($author <> "" ) {
            $args['post_author'] = $author;
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

    /**
     * Create entry
     * Load the course setup page with course content, memberships,
     * online tutor and course certificates
     *
     * @return array $courseContent
     * @return array $memberships
     * @return array $onlineTutor
     * @return array $courseCertificates
     * @return mixed|string
     * @throws Exception
     */

    public function create()
    {
        $getOptions = get_option('the-course-content');

        // This courseContent will serve as the course template for one-click
        $courseContent = $this->getCourseContents($getOptions);

        // Get memberships
        $memberships = $this->getCourseMemberships();

        // Online Tutor

        $onlineTutor = $this->getTutors();

        // Course Certificate
        $courseCertificates = $this->getCertificates();
   
        
        return (new View('steps/steps'))
            ->with('memberships',$memberships)
            ->with('courseContent', $courseContent )
            ->with('onlineTutor',$onlineTutor)
            ->with('courseCertificates',$courseCertificates)
            ->render();

    }

    
    /**
     * Delete Classroom
     *
     * @param Request $request
     * @throws \Exception
     */
    public function delete(Request $request){
        $course = new Posts;
        $id = $request->input('id');
       
        $data = $course->delete($id);
        echo json_encode($data);
        die();
        
    }

    public function test(Request $request)
    {
        var_dump($request);
    }

}
