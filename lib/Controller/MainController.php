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




        return (new View('dashboard/dashboard'))->render();
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
                        'orderby'    => 'display_name'
                       ]);

        // Course Certificate
        $courseCertificates = $posts->select(['ID, post_title'])->where('post_type', 'sfwd-certificates')->orderBy('post_title')->results();


        return (new View('steps/steps'))
            ->with('memberships',$memberships)
            ->with('courseContent', $courseContent )
            ->with('onlineTutor',$onlineTutor)
            ->with('courseCertificates',$courseCertificates)
            ->render();

    }


    public function test(Request $request)
    {
        var_dump($request);
    }

}
