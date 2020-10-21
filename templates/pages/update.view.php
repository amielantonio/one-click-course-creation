<br>
<br>


<?php
if ($data->have_posts()){
            

	while($data->have_posts()) : $data->the_post();
	    acf_form_head();
	    acf_form(
	            array(
	            'id' => 'dialy_digest_data',
	            'fields' => array(
	                            'field_5d2215983619d',
	                            'field_5f50887e3bd91'
	                        )
	            )
	      );
	    
	    // print_r(get_field_objects());
	endwhile;

} else {
		echo "NO data";
}
?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
	$(document).on('click','#dialy_digest_data input[type=checkbox]',function(){

		// unchecked all checkbox
		$('#dialy_digest_data input[type=checkbox]').each(function(){
			$(this).prop('checked',false);
		});

		//	var user_check = [0].attr('id');
		$(this).prop("checked",true);
	});
</script>
