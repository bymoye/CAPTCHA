<?php include('./CAPTCHA.php');
$test = new CAPTCHA();
echo $_COOKIE["timestamp"]??null;
$message = null;
if(isset($_COOKIE["timestamp"]) && !empty($_POST['yzm']) && isset($_COOKIE["id"])){
    $message =  $test->check_CAPTCHA($_POST['yzm']);
}
$test2 = $test->create_captcha_img();
$test3 = json_decode($test2);
header("Content-type:text/html;charset=utf-8;Cache-Control: no-cache, must-revalidate");
?>
<html>

<head>
    <title>测试页</title>
</head>

<body>
    <?php echo var_dump(json_decode($message))?>

    <form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
        <img src="<?php echo $test3->data?>">
            <input type="text" name="yzm">
        <input type="submit" value="提交">
    </form>
</body>

</html>