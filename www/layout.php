<!DOCTYPE html>
<html>
	<head>
		<title><?php echo htmlspecialchars( $title ) ?></title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<div class="container">
			<h1>WordPress API Example Client</h1>

			<?php if ( $error ): ?>

				<div class="warn"><?php echo $error ?></div>

			<?php endif ?>

			<?php echo $content ?>
		</div>
	</body>
</html>
