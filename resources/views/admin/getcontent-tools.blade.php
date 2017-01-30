<!DOCTYPE html>
<html lang="en">
    <head>
		<title>My page</title>
		<link rel="stylesheet" type="text/css" href="../vendor/content-tools/content-tools.min.css">
		<link rel="stylesheet" type="text/css" href="../vendor/fancybox/jquery.fancybox.css">
		<style>
			/* Alignment styles for images, videos and iframes in editable regions */

			/* Center (default) */
			[data-editable] iframe,
			[data-editable] image,
			[data-editable] [data-ce-tag=img],
			[data-editable] img,
			[data-editable] video {
				clear: both;
				display: block;
				margin-left: auto;
				margin-right: auto;
				max-width: 100%;
			}

			/* Left align */
			[data-editable] .align-left {
				clear: initial;
				float: left;
				margin-right: 0.5em;
			}

			/* Right align */
			[data-editable].align-right {
				clear: initial;
				float: right;
				margin-left: 0.5em;
			}

			/* Alignment styles for text in editable regions */
			[data-editable] .text-center {
				text-align: center;
			}

			[data-editable] .text-left {
				text-align: left;
			}

			[data-editable] .text-right {
				text-align: right;
			}
		</style>
	</head>
	<body>
		<script src="../vendor/jquery.js"></script>
		<script src="../vendor/fancybox/jquery.fancybox.js"></script>
		<script src="../vendor/content-tools/content-tools.js"></script>
		<script src="../vendor/content-tools/editor.js"></script>
		
		<div data-editable data-name="main-content">
			<blockquote>
				Always code as if the guy who ends up maintaining your code will be a violent psychopath who knows where you live.
			</blockquote>
			<p>John F. Woods</p>
		</div>
	</body>
</html>
