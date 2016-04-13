<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Replace Selected Textarea Elements &mdash; CKEditor Sample</title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
	<link href="../sample.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <?php
	$data=$_REQUEST["data"];
	?>
	<form action="../sample_posteddata.php" method="post">
		<p>
			<label for="editor1">
				Editor 1:</label>
			<textarea cols="80" id="editor1" name="editor1" rows="10">
			<?php echo $data; ?>
			</textarea>
		</p>
		<p>
			<input type="submit" value="Submit"/>
		</p>
	</form>
	<?php
	// Include the CKEditor class.
	include_once "../../ckeditor.php";
	// Create a class instance.
	$CKEditor = new CKEditor();
	// Path to the CKEditor directory, ideally use an absolute path instead of a relative dir.
	//   $CKEditor->basePath = '/ckeditor/'
	// If not set, CKEditor will try to detect the correct path.
	$CKEditor->basePath = '../../';
	// Replace a textarea element with an id (or name) of "editor1".
	$CKEditor->replace("editor1");
	?>
</body>
</html>
