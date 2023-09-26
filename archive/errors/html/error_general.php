<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php require_once PATH_ARCHIVE.'errors/header.php'; ?>

<div class="error">
	<div id="container">
		<h2><?php echo $heading; ?></h2>
		<?php echo $message; ?>
	</div>
</div>

<?php require_once PATH_ARCHIVE.'errors/footer.php'; ?>