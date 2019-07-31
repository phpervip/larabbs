    <?php

    /**
    * 抓取网站上的图片到本地
    * PS: 如果网页中的图片路径不是绝对路径，就无法抓取
    */

    set_time_limit(0);
    //抓取不受时间限制
    $URL='https://720yun.com/t/3e6jO7kksy5?scene_id=3618380';
    //任意网址
    get_pic($URL);

    function get_pic($pic_url) {
    //获取图片二进制流
        $data=CurlGet($pic_url);      /*利用正则表达式得到图片链接*/
        $pattern_src = '/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/';
        $num = preg_match_all($pattern_src, $data, $match_src);
        $arr_src=$match_src[1];//获得图片数组
        print_r($arr_src);
        get_name($arr_src);
        echo "<br>finished!!!";
        return 0;
    }
    /*得到图片类型，并将其保存到与该文件同一目录*/
    function get_name($pic_arr) {
    //图片类型
        $pattern_type = '/(\.(jpg|bmp|jpeg|gif|png))/';
        foreach($pic_arr as $pic_item){
        //循环取出每幅图的地址
            $num = preg_match_all($pattern_type, $pic_item, $match_type);
            if($num){
                $pic_name = get_unique().$match_type[1][0];
                //改时微秒时间戳命名
                //以流的形式保存图片
                $write_fd = @fopen('./getpic/'.$pic_name,"wb");
                @fwrite($write_fd, CurlGet($pic_item));
                @fclose($write_fd);
                echo "[OK]..!";
            }
        }
        return 0;
    }
    //通过微秒时间获得唯一ID
    function get_unique(){
        list($msec, $sec) = explode(" ",microtime());
        return $sec.intval($msec*1000000);
    }
    //抓取网页内容
    function CurlGet($url){
        $url=str_replace('&amp;','&',$url);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        //curl_setopt($curl, CURLOPT_REFERER,$url);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; SeaPort/1.2; Windows NT 5.1; SV1; InfoPath.2)");
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        $values = curl_exec($curl);
        curl_close($curl);
        return $values;
    }
 ?>
