<?php
namespace AWC\Controller;

use AWC\Helpers\Func;
use AWC\Helpers\View;
use AWC\Core\Request;
use AWC\Core\CoreController;
use AWC\Model\AccessDevice;
use AWC\Model\PostMeta;
use WP_Query;
use AWC\Model\Posts;
use Exception;

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

//        var_dump($posts);
        
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
        global $wpdb;
        $posts = new Posts;
        $postMeta = new PostMeta;

        $getOptions = get_option('the-course-content');
        $courseContent = [];

        //Get course content data
        if(!empty($getOptions)) {
            foreach($getOptions as $getOption) {
                $courseSelected = get_post($getOption);
                $courseContent[$courseSelected->ID]['course_name'] = $courseSelected->post_title;

                $lessons = learndash_get_course_lessons_list($getOption);

                foreach($lessons as $lesson) {
                    $courseContent[$courseSelected->ID]['lessons'][] = [
                        'lesson-id' => $lesson['post']->ID,
                        'lesson-title' => $lesson['post']->post_title,
                    ];
                    $courseContent[$courseSelected->ID]['post_meta'] = [
                        'awc_active_course' => get_post_meta($getOption, 'awc_active_course')[0],
                        'collapse_replies_for_course' => get_post_meta($getOption, 'collapse_replies_for_course')[0],
                        'awc_private_comments' => get_post_meta($getOption, 'awc_private_comments')[0],
                        'email_daily_comment_digest' => get_post_meta($getOption, 'email_daily_comment_digest')[0],
                        'cc_recipients' => get_post_meta($getOption, 'cc_recipients'),
                        'tag_ids' => explode(', ',get_post_meta($getOption, '_is4wp_access_tags')[0]),
                        'certificate' => get_post_meta($getOption, '_sfwd-courses')[0]['sfwd-courses_certificate'],
                        'excluded_keywords' => get_option('exclude-module-keywords'),
                    ];
                }
            }
        }

        // Get memberships
        $memberium = get_option('memberium');
        $memberships = [];
        if(isset($memberium['memberships'])){
            // GET THE TAG LIST
            $tags = [];
            $table = 'memberium_tags';
            $appname = "lf159"; # memberium_tags table appname field

            $sql = "SELECT id, name FROM {$table} WHERE `appname` = '{$appname}' ORDER BY category, name ";
            $result = $wpdb->get_results($sql, ARRAY_A);
            foreach ($result as $data) {
                $tags['mc'][$data['id']] = $data['name'];
            }

            $tags = $tags['mc'];
            // INCLUDE TAG ON LIST
            foreach ($memberium['memberships'] as $key => $data) {
                $tag = !empty($tags[$key]) ? $tags[$key]." ({$key})" : '(Missing Tag)';
                $memberium['memberships'][$key]['tag_name']  =  $tag;
            }

            $memberships = $memberium['memberships'];
        }
       

        // Online Tutor
        $onlineTutor =  get_users([
                        'role__in' => [ 'Administrator', 'group_leader'],
                        'fields'   => ['ID','user_email','display_name'],
                        'orderby'  => 'display_name'
                       ]);

        
        // Course Certificate
        $courseCertificates = $posts->select(['ID, post_title'])->where('post_type', 'sfwd-certificates')->orderBy('post_title')->results();
   
        // FOR EDIT
        $course_info = [];
        if(isset($_GET['p_id']) && !empty($_GET['p_id'])){
            $sql = "SELECT * FROM wp_posts WHERE ID = {$_GET['p_id']} LIMIT 1;";
            $classroom = $wpdb->get_results($sql);
           
            if(count($classroom) > 0){
                foreach($classroom as $post){
                   
                    $course_info[$post->ID]['post_author'] = $post->post_author;
                    $course_info[$post->ID]['course_name'] = $post->post_title;
                    $lessons = learndash_get_course_lessons_list($post->ID);
        
                    foreach($lessons as $lesson) {
                        $course_info[$post->ID]['lessons'][] = [
                            'lesson-id' => $lesson['post']->ID,
                            'lesson-title' => $lesson['post']->post_title,
                        ];
                        $course_info[$post->ID]['post_meta'] = [
                            'awc_active_course' => get_post_meta($post->ID, 'awc_active_course')[0],
                            'collapse_replies_for_course' => get_post_meta($post->ID, 'collapse_replies_for_course')[0],
                            'awc_private_comments' => get_post_meta($post->ID, 'awc_private_comments')[0],
                            'email_daily_comment_digest' => get_post_meta($post->ID, 'email_daily_comment_digest')[0],
                            'cc_recipients' => get_post_meta($post->ID, 'cc_recipients'),
                            'tag_ids' => explode(', ',get_post_meta($post->ID, '_is4wp_access_tags')[0]),
                            'certificate' => get_post_meta($post->ID, '_sfwd-courses')[0]['sfwd-courses_certificate'],
                            'excluded_keywords' => get_option('exclude-module-keywords')
                        ];
                    }
                }
            }
        }


        return (new View('steps/steps'))
            ->with('memberships',$memberships)
            ->with('courseContent', $courseContent )
            ->with('onlineTutor',$onlineTutor)
            ->with('courseCertificates',$courseCertificates)
            ->with('course_info',$course_info)
            ->render();

    }

    
    /**
     * View Classroom
     *
     * @param Request $request
     * @throws \Exception
     */
    public function classroom_update_page(Request $request){
        global $wpdb;
        $id = $_GET['post_id'];
        $posts = new Posts;
        $course = new Posts;
       
        $classroom = $course->select(['*'])->where('ID',$id)->results();
        $courseContent = [];

        $post_info = $classroom;

        foreach($classroom as $post){

            $courseContent[$post->ID]['course_name'] = $post->post_title;
            $lessons = learndash_get_course_lessons_list($post->ID);

            foreach($lessons as $lesson) {
                $courseContent[$courseSelected->ID]['lessons'][] = [
                    'lesson-id' => $lesson['post']->ID,
                    'lesson-title' => $lesson['post']->post_title,
                ];
                $courseContent[$post->ID]['post_meta'] = [
                    'awc_active_course' => get_post_meta($post->ID, 'awc_active_course')[0],
                    'collapse_replies_for_course' => get_post_meta($post->ID, 'collapse_replies_for_course')[0],
                    'awc_private_comments' => get_post_meta($post->ID, 'awc_private_comments')[0],
                    'email_daily_comment_digest' => get_post_meta($post->ID, 'email_daily_comment_digest')[0],
                    'cc_recipients' => get_post_meta($post->ID, 'cc_recipients'),
                    'tag_ids' => explode(', ',get_post_meta($post->ID, '_is4wp_access_tags')[0]),
                    'certificate' => get_post_meta($post->ID, '_sfwd-courses')[0]['sfwd-courses_certificate'],
                    'excluded_keywords' => get_option('exclude-module-keywords')
                ];
            }
        }

       
        // Get memberships
        $memberium = get_option('memberium');
        $memberships = [];
        if(isset($memberium['memberships'])){
            // GET THE TAG LIST
            $tags = [];
            $table = 'memberium_tags';
            $appname = "lf159"; # memberium_tags table appname field

            $sql = "SELECT id, name FROM {$table} WHERE `appname` = '{$appname}' ORDER BY category, name ";
            $result = $wpdb->get_results($sql, ARRAY_A);
            foreach ($result as $data) {
                $tags['mc'][$data['id']] = $data['name'];
            }

            $tags = $tags['mc'];
            // INCLUDE TAG ON LIST
            foreach ($memberium['memberships'] as $key => $data) {
                $tag = !empty($tags[$key]) ? $tags[$key]." ({$key})" : '(Missing Tag)';
                $memberium['memberships'][$key]['tag_name']  =  $tag;
            }

            $memberships = $memberium['memberships'];
        }

        // Online Tutor
        $onlineTutor =  get_users([
                        'role__in' => [ 'Administrator', 'group_leader'],
                        'fields'   => ['ID','user_email','display_name'],
                        'orderby'  => 'display_name'
                       ]);

        // Course Certificate
        $courseCertificates = $posts->select(['ID, post_title'])->where('post_type', 'sfwd-certificates')->orderBy('post_title')->results();

        return (new View('steps/updateCourse'))
                    ->with('memberships',$memberships)
                    ->with('courseContent', $courseContent )
                    ->with('onlineTutor',$onlineTutor)
                    ->with('courseCertificates',$courseCertificates)
                    ->with('post_info',$post_info)
                    ->render();
    }

    /**
     * Update Classroom
     *
     * @param Request $request
     * @throws \Exception
     */
    public function update(Request $request){
        
        
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
