<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php include APPPATH.'views/errors/header.php'; ?>

<div class="error exception">

	<div>
		<h2>A <span>PHP Error</span> was encountered</h2>
		<code>
			<a target="_blank" href="http://google.com/search?q=<?php echo $severity; ?> <?php echo $message; ?>"><?php echo $message; ?></a>
		</code>
		
		<!-- <p>Message:  <?php echo $message; ?></p> -->
		<p>Filename: <?php echo $filepath; ?></p>
		<p>Severity: <?php echo $severity; ?></p>
		<p>Line Number: <?php echo $line; ?></p>
	</div>
	<div class="backtrace">
	<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

		<h3>Backtrace:</h3>
		<?php foreach (debug_backtrace() as $error): ?>

			<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

				<div>
				File: <?php echo $error['file'] ?><br />
				Line: <?php echo $error['line'] ?><br />
				Function: <?php echo $error['function'] ?>
				</div>

			<?php endif ?>

		<?php endforeach ?>

	<?php endif ?>
	</div>

</div>
<?php include APPPATH.'views/errors/footer.php'; ?>