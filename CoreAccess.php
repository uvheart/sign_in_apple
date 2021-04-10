<?php





require_once   './phpseclib/phpseclib/phpseclib/Crypt/RSA.php';
require_once   './phpseclib/phpseclib/phpseclib/Math/BigInteger.php';
require_once   './phpseclib/phpseclib/phpseclib/Crypt/Hash.php';
require_once   './JWT/JWT.php';
require_once   './JWT/ExpiredException.php';
require_once   './JWT/SignatureInvalidException.php';
require_once   './JWT/BeforeValidException.php';



class CoreAccess
{


    /**
     * 传入token,openid
     * @param $accessToken
     * @param $openId
     * @return bool
     */
    public function index($accessToken, $openId){



        $publicKey = $this->_getPublicKeyByApple();
        if($publicKey){

            $keys_map=[];
            foreach($publicKey['keys'] as $key){
                $keys_map[$key['kid']]=$key;
            }
            // 定位用于加密当前 identityToken 的 JWK
            $tks = explode('.', $accessToken);
            list($headb64, $bodyb64, $cryptob64) = $tks;


            $header=\JWT::jsonDecode(\JWT::urlsafeB64Decode($headb64));
            $key_used=$keys_map[$header->kid];
            $rsa = new \RSA();
            $rsa->loadKey(
                [
                    'e' => new \BigInteger(base64_decode($key_used['e']), 256),
                    'n' => new \BigInteger(base64_decode(strtr($key_used['n'], '-_', '+/'), true), 256)
                ]
            );
            $publicKey =  $rsa->getPublicKey();
            $decode=\JWT::decode($accessToken,$publicKey,['RS256']);
            $decode = self::objectToArray($decode);

            if((isset($decode['sub']) && $openId == $decode['sub']) && (isset($decode['iss']) && $decode['iss'] == 'https://appleid.apple.com') && (isset($decode['exp']) && $decode['exp'] >= time())){
                $result = true;
            }
        }
        return $result;
    }

    private function _getPublicKeyByApple(){
      //这个public key可以考虑缓存到redis，减少网络请求
        $url = 'https://appleid.apple.com/auth/keys';
        $response =  $this->requestDataCurl($url,[]);
        return  json_decode($response,true);


    }

    //PHP stdClass Object转array
    public static function objectToArray($array) {
        if(is_object($array)) {
            $array = (array)$array;
        }
        if(is_array($array)) {
            foreach($array as $key=>$value) {
                $array[$key] = self::objectToArray($value);
            }
        }
        return $array;
    }

    public static function randUserName($username)
    {
        $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUV12345';
        $arr = str_split($string);
        for($i =0;$i<10;$i++){
            $temp = rand(0,57);
            $username.=$arr[$arr[$temp]];

        }

        return $username;
    }


    /**
     * A summary informing the user what the associated element does.
     *
     *
     * @param       $url
     * @param array $data
     * @param int   $time  秒数或毫秒数
     * @param       $timeType  0 秒  1毫秒
     *
     * @return array|mixed
     * @throws \Exception
     */
    public  function requestDataCurl($url, $data = [], $time=5, $timeType = 0, $contentType = ''){
        $ch = curl_init();
        //设置超时
        if($timeType){
            curl_setopt($ch, CURLOPT_NOSIGNAL,1);    //注意，毫秒超时一定要设置这个
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $time);  //超时毫秒
        } else{
            curl_setopt($ch, CURLOPT_TIMEOUT, $time); // 超时秒
        }

        curl_setopt($ch,CURLOPT_URL, $url);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        if ($contentType) {
            curl_setopt($ch, CURLOPT_HTTPHEADER,     ['Content-Type: '.$contentType]);
        }

        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $err_no = curl_errno($ch);
            $err_msg = curl_error($ch);
            curl_close($ch);
            throw new \Exception("curl出错，url:{$url}, data:" . print_r($data, true) . ", 错误码:{$err_no}，错误信息:{$err_msg}");
        }
    }
}