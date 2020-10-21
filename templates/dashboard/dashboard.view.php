<!--<form action="?page=one-click-classroom-setup&route=test" method="get">-->
<form action="<?php echo admin_url( 'admin.php'); ?>" method="get">
    <input type="hidden" name="page" value="one-click-classroom-setup" />
    <input type="hidden" name="route" value="test" />
    <input type="text" name="firstname">
    <input type="text" name="lastname">
    <button type="submit">Pass</button>
</form>


<h1 class="_m-b--50">Created Classrooms</h1>

<div class="oc-container">
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
                    <th>Module Schedule</th>
                    <th>Settings</th>
                    <th>Date Created</th>
                    <th>Author</th>
                    <th>Options</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td>1</td>
                    <td>Test</td>
                    <td>Test</td>
                    <td>test</td>
                    <td>test</td>
                    <td>test</td>
                    <td></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Test</td>
                    <td>Test</td>
                    <td>test</td>
                    <td>test</td>
                    <td>test</td>
                    <td></td>
                </tr>
                </tbody>

                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Course Title</th>
                    <th>Module Schedule</th>
                    <th>Settings</th>
                    <th>Date Created</th>
                    <th>Author</th>
                    <th>Options</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function () {
    $('#tbl-created-tables').DataTable({});
  });
</script>
