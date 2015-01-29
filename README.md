# gogoig

PHP Instagram API ver 0.1

## How to use

1. Go to [http://instagram.com/developer/](http://instagram.com/developer/) to get a client id.

2. Include gogoig.php .

3. Code below:

        $ig = new gogoig(YOUR_CLIENT_ID);
        $result = $ig->getUserMedia(USER_NAME, 2);

    Result will be a array such like:

        Array
        (
            [0] => Array
                (
                    [url] => http://image_path.jpg
                    [width] => 640
                    [height] => 640
                    [created_time] => 1420972680
                )
            [1] => Array
                (
                    [url] => http://image_path.jpg.jpg
                    [width] => 640
                    [height] => 640
                    [created_time] => 1420877820
                )
        )

        
## Full paragrams 

$count : Count of media to return.

$max_timestamp : Return media before this UNIX timestamp.


        $ig->getUserMedia(USER_NAME, $count, $max_timestamp);


## Get someone's all public images 

        $ig = new gogoig(YOUR_CLIENT_ID);
        $user_media = array(); // here you are
        $more_flag = true;
        $user_name = 'akiyo0414';
        $count = 30;
        $max_timestamp = '';
        
        do {
            $arr = $ig->getUserMedia($user_name,$count,$max_timestamp);
            if ( count($arr) > 0) {
                $user_media = array_merge($user_media , $arr);
                $last_one = array_pop($arr);
                $max_timestamp = $last_one['created_time'];
                echo $max_timestamp.'<br >';
                echo count($user_media).'<br>';
            } else {
                $more_flag = false;
            }
        } while($more_flag);
