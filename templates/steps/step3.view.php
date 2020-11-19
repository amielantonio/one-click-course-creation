<h3 class="cohort-title">Course Settings</h3>
<?php
    if(!empty($course_info)){
        $aac = $course_info[$_GET['p_id']]['post_meta']['awc_active_course'] == 1 ? 'checked' : '';
        $apc = $course_info[$_GET['p_id']]['post_meta']['awc_private_comments'] == 1 ? 'checked' : '';
        $edcd = $course_info[$_GET['p_id']]['post_meta']['email_daily_comment_digest'] == 1 ? 'checked' : '';
        $cc = $course_info[$_GET['p_id']]['post_meta']['cc_recipients'][0];

        $crfc = !empty($course_info[$_GET['p_id']]['post_meta']['collapse_replies_for_course']) ? 'checked' : '';
       
    } else {
        $crfc = '';
        $cc = '';
        $aac = '';
        $apc = '';
        $edcd = '';
    }
?>
<div class="oc-checkbox-row">
    <div class="oc-checkbox-row--left">
        <input type="checkbox" id="awc_active_course" name="awc_active_course" <?= $acc; ?>>
    </div>
    <div class="oc-checkbox-row--right">
        <label for="">Active Course</label>
        <p class="information">Mark the course as an active course</p>
    </div>
</div>

<hr class="divider">

<div class="oc-checkbox-row">
    <div class="oc-checkbox-row--left">
        <input type="checkbox" id="email_daily_comment_digest" name="email_daily_comment_digest" <?= $edcd; ?>>
    </div>
    <div class="oc-checkbox-row--right">
        <label for="">Daily Digests</label>
        <p class="information">Daily email from AWC</p>
    </div>
</div>

<div class="oc-form-group">
    <label>CC Recipients</label>
    <p class="information">NEW LINE separated list of email addresses, who will receive a copy of this email when sent</p>
    <textarea name="cc_recipients" id="cc_recipients" cols="30" rows="7"><?=  $cc; ?></textarea>
</div>

<div class="oc-checkbox-row">
    <div class="oc-checkbox-row--left">
        <input type="checkbox" id="awc_private_comments" name="awc_private_comments" <?= $apc; ?> >
    </div>
    <div class="oc-checkbox-row--right">
        <label for="">Private Comments</label>
        <p class="information">Allow private comments for this course</p>
    </div>
</div>

<hr class="divider">

<div class="oc-checkbox-row">
    <div class="oc-checkbox-row--left">
        <input type="checkbox" id="collapse_replies_for_course" name="collapse_replies_for_course" <?= $crfc?>>
    </div>
    <div class="oc-checkbox-row--right">
        <label for="">Collapse Replies</label>
        <p class="information">Collapse replies for this course</p>
    </div>
</div>
