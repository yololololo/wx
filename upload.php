<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>上传素材</title>
</head>
<body>
<?php include('func.php');  ?>
<form method="post" action="<?php echo sucaiUrl('image'); ?>" enctype="multipart/form-data">
<input type="file" name="media">
<input type="submit" name="" value="提交">
</form>
</body>
</html>
