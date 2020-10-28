export default function private_or_digestive() {
  let $privateComment = $('#email_daily_comment_digest');
  let $dailyDigest = $('#awc_private_comments');

  $privateComment.on('click', function(){

    if($(this).is(':checked') && $dailyDigest.is(':checked')) {

      alert('You can only select either private comments or email digest option but not both');
      $dailyDigest.prop('checked', false );
    }

  });

  $dailyDigest.on('click', function(){

    if($(this).is(':checked') && $privateComment.is(":checked")) {
      alert('You can only select either private comments or email digest option but not both');
      $privateComment.prop('checked', false );
    }
  });
}
