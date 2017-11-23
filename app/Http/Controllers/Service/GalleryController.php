<?php
/**
 * Created by PhpStorm.
 * User: wenghang
 * Date: 2017/9/17
 * Time: 上午9:51
 */

namespace App\Http\Controllers\Service;


use GuzzleHttp\Client;
use phpseclib\Crypt\RSA;
use phpseclib\Math\BigInteger;

class GalleryController
{
    const PRE_LOGIN_URL = 'https://login.sina.com.cn/sso/prelogin.php?entry=account&callback=sinaSSOController.preloginCallBack&su=bGl1ZGl3ZWkxOCU0MHNpbmEuY29t&rsakt=mod&client=ssologin.js(v1.4.18)';

    const LOGIN_URL = 'http://login.sina.com.cn/sso/login.php?client=ssologin.js(v1.4.18)';

    const USERNAME = '18659740512';

    const PASSWORD = '0523...wa+';

    private $pre;

    public function gallery(){
        $client = new Client();

        $rs = $client->get(self::PRE_LOGIN_URL);
        $rs = $rs->getBody()->getContents();

        $rs = str_before(str_after($rs, '('), ')');

        $this->pre = json_decode($rs, true);

        $su = base64_encode(urlencode(self::USERNAME));

        $sp = $this->getSp();

        $header = [
            "Host" => "login.sina.com.cn",
            "Proxy-Connection" => "keep-alive",
            "Cache-Control" => "max-age=0",
            "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Origin" => "http://weibo.com",
            "Upgrade-Insecure-Requests" => "1",
            "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36",
            "Referer" => "http://weibo.com",
            "Accept-Language" => "zh-CN,zh;q=0.8,en;q=0.6,ja;q=0.4",
            "Content-Type" => "application/x-www-form-urlencoded"
        ];

        $params = [
            'cdult'=> 3,
            'domain'=> 'http://sina.com.cn',
            'encoding'=> 'UTF-8',
            'entry'=> 'account',
            'from' => '',
            'gateway'=> 1,
            'nonce'=> $this->pre['nonce'],
            'pagerefer'=> 'http://login.sina.com.cn/sso/logout.php',
            'prelt'=> 41,
            'pwencode'=> 'rsa2',
            'returntype'=> 'TEXT',
            'rsakv'=> $this->pre['rsakv'],
            'savestate'=> 30,
            'servertime'=> $this->pre['servertime'],
            'service'=> 'sso',
            'sp'=> $sp,
            'sr'=> '1366*768',
            'su'=> $su,
            'useticket'=> 0,
            'vsnf'=> 1,
        ];

        $rs = $client->post(self::LOGIN_URL, [
            'headers'    =>  $header,
            'form_params'   =>  $params,
        ]);

        $rs = $rs->getBody()->getContents();

        dd($rs);

    }

    public function getSp(){
        $rsa = new RSA();

        $modulus = new BigInteger(bin2hex($this->pre['pubkey']));

        $component = new BigInteger(65537);

        $key = $rsa->_convertPublicKey($modulus, $component);

        $rsa->loadKey($key);
        $plintext = $this->pre['servertime'] . "\t" . $this->pre['nonce'] . "\n" . self::PASSWORD;

        return $rsa->encrypt($plintext);
    }

    public function upload(){
        $url = 'https://upload.api.weibo.com/2/statuses/upload.json';
        $accessToken = '2.00hF4vtBLX9vWCe5024ed7e3TDSpbD';

        $client = new Client();
        $rs = $client->post($url, [
            'multipart' => [
                [
                    'name'     => 'pic',
                    'contents' => fopen(storage_path('app/public/img.jpg'), 'r')
                ],
                [
                    'name'     => 'status',
                    'contents' => urlencode('hello world')
                ],
                [
                    'name'     => 'access_token',
                    'contents' => $accessToken
                ],
            ]
        ]);

        $rs = $rs->getBody()->getContents();

        dd($rs);
    }
}