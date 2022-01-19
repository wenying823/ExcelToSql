<!DOCTYPE html>
<html>
<head>
	<title>Excel To SQL</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>

<div class="container">
	<h1>上傳檔案</h1>

	<form method="POST" action="excelUpload.php" enctype="multipart/form-data">
		<div class="form-group">
			<label>上傳檔案</label>
			<input type="file" name="file" class="form-control">
		</div>
		<div class="form-check-group">
			<input type="checkbox" class="form-check-input" id="output_sql" checked="checked" name="output_sql">
			<label class="form-check-label" for="output_sql">輸出sql語法</label>
			<input type="checkbox" class="form-check-input" id="mysqli_check" name="mysqli_check">
			<label class="form-check-label" for="mysqli_check">執行sql語法</label>
		</div>
		<div class="form-group">
			<button type="submit" name="Submit" class="btn btn-success">Upload</button>
		</div>

	</form>
</div>

</body>
</html>