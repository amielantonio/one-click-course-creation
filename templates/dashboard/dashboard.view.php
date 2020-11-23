<div class="oc-container">

    <h1>One-Click Course</h1>

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
                        <td class="hover-toolbox">
                            <a href='<?= get_site_url()."/wp-admin/post.php?post=".$key."&action=edit"?>' target='_blank'><?= $value['course_name'] ?></a>
                            <div class="toolbox _m-t--30">
                                <a class="tool" href='<?php echo _channel('classroom-view', ['posts'=>$key])?>' target='_blank'>View Course</a>
                                <a class="tool" href='<?php echo _channel('classroom-view', ['posts'=>$key])?>' target='_blank'>Edit in Learndash</a>
                                <a class="tool" href='<?php echo "admin.php?page=course-setup&p_id={$key}" ?>' target='_blank'>Edit in One-click Classroom</a>
                                <a class="tool delete_one_click_data _text-red" href="#" data-id="<?php echo $key;?>" >Delete</a>
                            </div>
                        </td>
                        <td><?php echo $value['author']?></td>
                        <td class="_text-center">
                            <?php echo $value['post_meta']['awc_active_course'] == '0'
                                ? '&#10005;'
                                : '&#10003;'?>
                        </td>
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
            { "width": "35%" },
            { "width": "15%" },
            { "width": "5%" },
            { "width": "10%" },
            { "width": "15%" },
        ],
        "order":[],
        "columnDefs": [ {
            "targets": [3,4,5],
            "orderable": false
        } ]
    });
  });

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
            console.log(response);
            me.closest('tr').remove();
        });
    }
  });

  $('tbody tr').hover( function(){

      $(this).find('.toolbox .tool').toggleClass('show');

  })


</script>
