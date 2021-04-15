<?php

class CAPTCHA
{
    private $font;
    private $timestamp;

    /**
     * CAPTCHA constructor.
     */
    public function __construct() {
        $this->font = 'C://Windows//Fonts//Arial.ttf';
        session_start();
        $this->timestamp = time();
    }

    private function create_CAPTCHA($seed) {
        mt_srand($seed);
        $arr = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
        shuffle( $arr );
        $rand_keys = array_rand( $arr, 4 );
        $str = '';
        foreach ( $rand_keys as $value ) {
            $str .= $arr[$value];
        }
        return $str;
    }

    /**
     * @return false|string
     */
    public function create_captcha_img() {
        //创建画布
        $img = imagecreatetruecolor(120, 40);
            $_SESSION["timestamp"] = $this->timestamp;
        //填充背景色
        $backcolor = imagecolorallocate($img, mt_rand(200, 255), mt_rand(100, 255), mt_rand(0, 255));
        imagefill($img, 0, 0, $backcolor);
        
        //创建验证码
        $str = $this->create_CAPTCHA($this->timestamp);
        //绘制文字
        for ( $i = 1; $i <= 4; $i++ ) {
            $span = floor(120 / (1+4));
            $stringcolor = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 100), mt_rand(0, 80));
            $file = $this->font;
            imagefttext( $img, 30, 2, $i*$span, 30, $stringcolor, $file, $str[$i-1] );
        }

        //添加干扰线
        for ( $i = 1; $i <= 8; $i++ ) {
            $linecolor = imagecolorallocate( $img, mt_rand( 0, 150 ), mt_rand( 0, 250 ), mt_rand( 0, 255 ) );
            imageline( $img, mt_rand( 0, 179 ), mt_rand( 0, 39 ), mt_rand( 0, 179 ), mt_rand( 0, 39 ), $linecolor );
        }

        //添加干扰点
        for ( $i = 1; $i <= 180*40*0.02; $i++ ) {
            $pixelcolor = imagecolorallocate( $img, mt_rand( 100, 150 ), mt_rand( 0, 120 ), mt_rand( 0, 255 ) );
            imagesetpixel( $img, mt_rand( 0, 179 ), mt_rand( 0, 39 ), $pixelcolor );
        }

        //打开缓存区
        ob_start ();
        imagepng($img);
        //输出图片
        $test =  ob_get_contents();
        //销毁缓存区
        ob_end_clean();
        //销毁图片(释放资源)
        imagedestroy($img);
        // 以json格式输出
        $test = 'data:image/png;base64,' . base64_encode($test);
        $json = array(
            'code'=>0,
            'data'=>$test,
            'timestamp'=>$this->timestamp,
            'msg'=>''
        ); 
        return json_encode($json);
    }

    /**
     * @param $captcha
     * @return false|string
     */
    public function check_CAPTCHA($captcha){
        $temp = time();
        $temp1 = $temp-60;
        if (!$captcha){
            $code = 3;
            $msg = '请输入验证码!';
        }
        elseif($_SESSION["timestamp"] < $temp1){
            $code = 2;
            $msg = '超时!';
        }
        elseif($_SESSION["timestamp"] >= $temp1){
            if (strtolower($captcha) === strtolower($this->create_CAPTCHA($_SESSION["timestamp"]))){
                $code = 0;
                $msg = '正确!';
            }else{
                $code = 1;
                $msg = '错误!';
        }
        }else{
            $code = 1;
            $msg = '错误!';
        }
        $json = array(
            'code'=>$code,
            'data'=>'',
            'timestamp'=>$_SESSION["timestamp"],
            'msg'=>$msg,
            'comparison'=>$this->create_CAPTCHA($_SESSION["timestamp"]),
            'CAPTCHA'=>$captcha
        ); 
        return json_encode($json);
    }
}