<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php include APPPATH.'views/errors/header.php'; ?>

<div class="error">
	<div id="container">
		<h2><?php echo $heading; ?></h2>
		<?php echo $message; ?>
	</div>
</div>

<?php include APPPATH.'views/errors/footer.php'; ?>