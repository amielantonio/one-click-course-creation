<div class="oc-container">

    <div style="display: flex;align-items: center" class="_m-b--50">
        <h1 class="_m-r--10">One-Click Course</h1>
        <a href="<?php echo  get_site_url()."/wp-admin/admin.php?page=course-setup" ?>" class="oc-btn oc-btn--primary oc-btn--small">Create Classroom</a>
    </div>

    <div class="oc-collection-container">
        <div class="container-head">
            <h3>Classrooms</h3>
        </div>
        <div class="container-body oc-table-stats">

            <table class="oc-table" id="tbl-created-tables">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Title</th>
                    <th>Author</th>
                    <th>Active Course</th>
                    <th>Tags</th>
                    <th>Date Created</th>
                </tr>
                </thead>

                <tbody>

                <?php if(isset($courseContent) && !empty($courseContent) ): ?>
                    <?php foreach($courseContent as $key => $value):?>
                    <tr>
                        <td><?php echo $key ?></td>
                        <!--COURSE NAME-->
                        <td class="hover-toolbox">
                            <a href='<?= get_site_url()."/wp-admin/post.php?post=".$key."&action=edit"?>' target='_blank'><?= $value['course_name'] ?></a> <?php echo ($value['post_status'] <> 'publish') ? "— {$value['post_status']}" : "" ?>
                            <div class="toolbox _m-t--30">
                                <a class="tool" href='<?php echo site_url()."/courses/{$value['course_slug']}"?>' target='_blank'>View Course</a>
                                <a class="tool" href='<?= get_site_url()."/wp-admin/post.php?post=".$key."&action=edit"?>'>Edit in Learndash</a>
                                <a class="tool" href='<?php echo _channel('classroom-edit', ['posts'=>$key], 'course-setup')?>'>Edit in One-click Classroom</a>
                                <a class="tool" href="#" data-id="<?php echo $key;?>">Trash</a>
                                <a class="tool delete_one_click_data _text-red" href="#" data-id="<?php echo $key;?>">Delete</a>
                            </div>
                        </td>
                        <!--AUTHOR NAME-->
                        <td>
                            <a href="<?php echo "admin.php?page=one-click-classroom-setup&author={$value['author']['id']}"?>"><?php echo $value['author']['name']?></a>
                        </td>
                        <!--ACTIVE COURSE-->
                        <td class="_text-center">
                            <?php echo $value['post_meta']['awc_active_course'] == '0'
                                ? '&#10005;'
                                : '&#10003;'?>
                        </td>
                        <!--MEMBERIUM TAGS-->
                        <td>
                            <?php
                            if(!empty($value['tag_info'])) {
                                foreach($value['tag_info'] as $tags){
                                    echo $tags['id']."<br>";
                                }
                            } else {
                                echo "-----";
                            } ?>
                        </td>
                        <!--DATE CREATED-->
                        <td><?php echo $value['date_created']?></td>
                    </tr>
                    <?php endforeach;?>
                <?php endif; ?>

                </tbody>

                <tfoot>
                <tr>
                <th>ID</th>
                    <th>Course Title</th>
                    <th>Author</th>
                    <th>Active Course</th>
                    <th>Tags</th>
                    <th>Date Created</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function () {
    $('#tbl-created-tables').DataTable({
        "columns": [
            { "width": "5%" },
            { "width": "40%" },
            { "width": "20%" },
            { "width": "5%" },
            { "width": "10%" },
            { "width": "20%" },
        ],
        "order":[],
        "columnDefs": [ {
            "targets": [3,4,5],
            "orderable": false
        } ]
    });
  });


  //Delete button on click
  $(document).on('click','.delete_one_click_data',function(){

    var ajaxurl = '<?php echo _route('classroom-delete'); ?>';
    var me = $(this);
    var id = me.attr('data-id');
    var confirmBox = confirm('Are you sure you want to delete this post? This action cannot be undone.');

    if (confirmBox == true) {
        let data = {
            id: id
        };
        $.post(ajaxurl, data, function (response) {
            me.closest('tr').remove();
        });
    }
  });

  //For the toolbox hover effect
  $('tbody tr').hover(function(){
      $(this).find('.toolbox .tool').addClass('show');
  }, function(){
      $(this).find('.toolbox .tool').removeClass('show');
  });




</script>
