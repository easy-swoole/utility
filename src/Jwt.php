<?php

namespace EasySwoole\Utility;

/**
 * JSON Web Tokens快速生成、校验
 * @package EasySwoole\Utility
 */
class Jwt
{
    private $alg = "AES";
    private $iss = "EasySwoole";
    private $exp = 7200; // 默认2个小时
    private $sub;
    private $nbf;
    private $with = [];

    private $secret_key;
    private $dataStr;
    private $headStr;
    private $signStr;

    public $errMsg;

    /**
     * @return mixed
     */
    public function getSecretKey()
    {
        return $this->secret_key;
    }

    /**
     * @param mixed $secret_key
     */
    public function setSecretKey($secret_key): void
    {
        $this->secret_key = $secret_key;
    }


    /**
     * @return string
     */
    public function getAlg(): string
    {
        return $this->alg;
    }

    /**
     * @param string $alg
     */
    public function setAlg(string $alg): void
    {
        $this->alg = $alg;
    }

    /**
     * @return string
     */
    public function getIss(): string
    {
        return $this->iss;
    }

    /**
     * @param string $iss
     */
    public function setIss(string $iss): void
    {
        $this->iss = $iss;
    }

    /**
     * @return int
     */
    public function getExp(): int
    {
        return $this->exp;
    }

    /**
     * @param int $exp
     */
    public function setExp(int $exp): void
    {
        $this->exp = $exp;
    }

    /**
     * @return mixed
     */
    public function getSub()
    {
        return $this->sub;
    }

    /**
     * @param mixed $sub
     */
    public function setSub($sub): void
    {
        $this->sub = $sub;
    }

    /**
     * @return mixed
     */
    public function getNbf()
    {
        return $this->nbf;
    }

    /**
     * @param mixed $nbf
     */
    public function setNbf($nbf): void
    {
        $this->nbf = $nbf;
    }

    /**
     * @return array
     */
    public function getWith(): array
    {
        return $this->with;
    }

    /**
     * @param array $with
     */
    public function setWith(array $with): void
    {
        $this->with = $with;
    }

    function make()
    {
        $this->makeHead();
        $this->makeData();
        $this->makeSign();
        return $this->jwt();
    }

    private function makeHead()
    {
        $this->headStr = base64_encode(json_encode([
            'alg' => $this->alg,
            'typ' => 'JWT',
        ]));
    }

    private function makeData()
    {
        $time       = time();
        $tem['iss'] = $this->iss;
        if (!empty($this->exp)) $tem['exp'] = ($time + $this->exp);
        $tem['sub'] = $this->sub;
        $tem['iat'] = $time;
        $tem['nbf'] = !empty($this->nbf) ? $this->nbf : $time; // 在此之前不可用
        if (!empty($this->with) && is_array($this->with)) $tem = array_merge($tem, $this->with);
        $this->dataStr = base64_encode(json_encode($tem));
    }

    private function makeSign()
    {
        // 选用签名
        switch ($this->alg) {
            case 'AES':
                $this->signStr = base64_encode(openssl_encrypt($this->headStr.".".$this->dataStr, 'AES-128-ECB', $this->secret_key, 0));
                break;
            default:
                $this->signStr = base64_encode(openssl_encrypt($this->headStr.".".$this->dataStr, 'AES-128-ECB', $this->secret_key, 0));
                break;
        }
    }

    private function jwt()
    {
        $str = $this->headStr.".".$this->dataStr.".".$this->signStr;
        $this->clear();
        return $str;
    }

    private function clear()
    {
        $this->setWith([]);
        $this->dataStr = '';
        $this->headStr = '';
        $this->signStr = '';
    }


    /**
     * 服务端解密
     * @param $str
     * @return mixed|string
     */
    public function decode($str)
    {
        if ($str == '') {
            $this->errMsg = '待解密字符串必传';
            return false;
        }

        $temArr = explode('.', $str);

        if (empty($temArr) || !is_array($temArr)){
            $this->errMsg = '待解密字符串格式错误';
            return false;
        }

        if (count($temArr) != 3){
            $this->errMsg = '待解密字符串格式错误';
            return false;
        }

        $this->headStr = $temArr[0];
        // 解head 拿算法
        $head = json_decode(base64_decode($this->headStr), TRUE);
        if (!empty($head['alg'])) {
            $this->alg = $head['alg'];
        } else {
            $this->clear();
            $this->errMsg = 'alg错误';
            return false;
        }
        $this->dataStr = $temArr[1];
        // 验证签名
        $this->makeSign();
        if ($temArr[2] !== $this->signStr) {
            $this->clear();
            $this->errMsg = '签名错误';
            return false;
        }
        $data = json_decode(base64_decode($this->dataStr), TRUE);

        $time = time();
        // 在此之前不可用
        if (!empty($data['nbf']) && ($data['nbf'] > $time)) {
            $this->clear();
            $this->errMsg = '未到达可使用时间';
            return false;
        }
        // 是否已经过期
        if (!empty($data['exp']) && ($data['exp'] < $time)) {
            $this->clear();
            $this->errMsg = '该token已经过期';
            return false;
        }
        // 返回解析数据
        return $data;
    }
}