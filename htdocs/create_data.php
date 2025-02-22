<!-- Create Data Page -->
<?php
	include_once '../private/common/initialization.php';
	$page_title = 'Add an Activity';
?>

<?php include_once 'common/top/top.php'; ?>

	<div class="f-island">

<?php
	// If the user is signed in display:
	if(isset($_SESSION['username'])):
?>

	<?php include_once 'common/data_form.php'; ?>
		
<?php
	else:
?>

	<p>You need to <a href="sign_up.php">sign up</a> for a RpTide account to add an activity.</p>
	<p>Already have an account? Cool: Just <a href="sign_in.php">sign in</a>.</p>

<?php
	endif;
?>

	</div>

<?php include_once 'common/bottom/bottom.php'; ?>