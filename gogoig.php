<?php

/**
 * gogoig - Instagram API class
 *
 * API Documentation: http://instagram.com/developer/
 * Class Documentation: https://github.com/itisjoe/gogoig
 *
 * @author Feng Hsin Chiao
 * @since 2015-01-29
 * @copyright Feng Hsin Chiao
 * @version 0.1
 * @license MIT http://opensource.org/licenses/MIT
 */

class gogoig {
    const NO_RESULT = 'no_result';
    const API_URL = 'https://api.instagram.com/v1';
    
    private $_client_id;
    private $_client_secret;
    
    public function __construct ($config) {
        if ( is_array($config) && $config['client_id'] && $config['client_secret']) {
            $this->setClientId($config['client_id']);
            $this->setClientSecret($config['client_secret']);
        } else if (is_string($config)) {
            $this->setClientId($config);            
        } else {
            throw new \Exception("Error: __construct() - missing config");
        }
    }
    
    public function getUserMedia($user_name, $count = 0, $max_timestamp = '') {
        $path = "/users/". $this->getUserId($user_name) ."/media/recent/";
        $params = array();
        if ($count > 0) {
            $params['count'] = $count;
        }
        if ($max_timestamp != '') {
            $params['max_timestamp'] = $max_timestamp;
        }
        $return = $this->sendCall($path, $params);
        $media_arr = array();
        if (is_array($return['data'])) {
            foreach ($return['data'] as $data) {
                $media_arr[] = array(
                    "url" => $data['images']['standard_resolution']['url']
                    ,"width" => $data['images']['standard_resolution']['width']
                    ,"height" => $data['images']['standard_resolution']['height']
                    ,"created_time" => $data['created_time']                    
                );
            }
        }
        return $media_arr;
    }

    protected function getUserId($user_name) {
        $path = "/users/search";
        $params = array("q"=>$user_name);
        $return = $this->sendCall($path, $params);
        $id = self::NO_RESULT;
        if (is_array($return['data'])) {
            foreach($return['data'] as $userData) {
                if ($userData['username'] == $user_name) {
                    $id = $userData['id'];
                    break;
                }
            }
        }
        return $id;
    }
    
    protected function sendCall($path, $params) {
        $url = self::API_URL . $path . "?client_id=". $this->getClientId();
        if (count($params) > 0) {
            $url .= "&" . $this->paramsToQueries($params);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($ch, CURLOPT_URL,$url);
        $result=curl_exec($ch);
        curl_close($ch);
        $_arr = json_decode($result, true);
        return $_arr;
    }
    
    protected function paramsToQueries ($params) {
        $string = "";
        if (is_array($params)) {
            $arr = array();
            foreach ($params as $k => $v) {
                $arr[] = $k . "=" . $v;
            }
            $string = implode("&", $arr);
        }
        return $string;
    }
    
    protected function setClientId($client_id) {
        $this->_client_id = $client_id;
    }

    protected function getClientId() {
        return $this->_client_id;
    }    

    protected function setClientSecret($client_secret) {
        $this->_client_secret = $client_secret;
    }

    protected function getClientSecret() {
        return $this->_client_secret;
    }
    
    

}

?>