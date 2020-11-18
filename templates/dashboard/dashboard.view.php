
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
 
<div class="oc-container">
    <div class="oc-collection-container">
        <div class="container-head">
            <h3>Classrooms</h3>
        </div>
        <div class="container-body oc-table-stats">

            <!-- model attachment testing-->
            <a href="<?php echo _channel('classroom-view', ['posts'=>109665])?>">view</a>
            <!-- model atachment testing end -->
            <table class="oc-table" id="tbl-created-tables">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Title</th>
                    <th>Author</th>
                    <th>Active Course</th>
                    <th>Tags</th>
                    <th>Date Created</th>
                    <th>Options</th>
                </tr>
                </thead>

                <tbody>
    
                <?php if(isset($courseContent) && !empty($courseContent) ): ?>
                    <?php foreach($courseContent as $key => $value):?>
                    <tr>
                        <td><?php echo $key ?></td>
                        <?php
                        // echo "<pre>";
                        // print_r($value);
                        ?>
                        <td style="font-size: 14px;text-transform: capitalize;"><?php echo "<a style='text-decoration:none;' href='".get_site_url()."/wp-admin/post.php?post=".$key."&action=edit' target='_blank'>".$value['course_name']."</a>"; ?></td>
                        <td style="font-size: 14px;text-transform: capitalize;"><?php echo $value['author']?></td>
                        <td style="font-size: 14px;text-transform: capitalize;"><?php echo $value['post_meta']['awc_active_course'] == '0' ? 'No' : 'Yes'?></td>
                        <td style="font-size: 14px;text-transform: capitalize;"><?php foreach($value['tag_info'] as $tags){
                             echo $tags['id']."<br>";
                        }?></td>
                        <td style="font-size: 14px;text-transform: capitalize;"><?php echo $value['date_created']?></td>
                        <td style="font-size: 14px;text-transform: capitalize;">
                        <p id="options" style="display:inline !important;">
                        <?php echo "<a style='text-decoration:none;' href='".get_site_url()."/wp-admin/post.php?post=".$key."&action=edit' target='_blank'>"?>view</a>

                        <?php echo "<a style='text-decoration:none;' href='".get_site_url()."/wp-admin/?page=course-setup&route=classroom-update-page&post_id=".$key."' target='_blank'>"?>edit</a>
                            <a style="text-decoration:none;" href="#" data-id="<?php echo $key;?>" class="delete_one_click_data">delete</a>
                        <p>
                        </td>
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
                    <th>Options</th>
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
            { "width": "15%" }
        ]
    });
  });

  $(document).on('click','.delete_one_click_data',function(){

    var ajaxurl = "admin.php?page=course-setup&route=classroom-delete";
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
</script>
