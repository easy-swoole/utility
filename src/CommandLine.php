<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/7/1
 * Time: 下午8:43
 */

namespace EasySwoole\Utility;

/*
 * CommandLine修改自网络
 */
class CommandLine
{
    private  $optsArr = [];
    private  $argsArr = [];
    private $optAutoCallback = [];
    private $argAutoCallback = [];
    const OPT_DEFAULT_CALLBACK = 'OPT_DEFAULT_CALLBACK';
    const ARG_DEFAULT_CALLBACK = 'ARG_DEFAULT_CALLBACK';

    public function setOptionCallback(string $optionName,callable $call)
    {
        $this->optAutoCallback[$optionName] = $call;
    }

    public function setArgCallback(string $arg,callable $call)
    {
        $this->argAutoCallback[$arg] = $call;
    }

    /**
     * 获取选项值
     * @param string|NULL $opt
     * @return array|string|NULL
     */
    public function getOptVal($opt = NULL) {
        if(is_null($opt)) {
            return $this->optsArr;
        } else if(isset( $this->optsArr[$opt])) {
            return  $this->optsArr[$opt];
        }
        return null;
    }

    /**
     * 获取命令行参数值
     * @param integer|NULL $index
     * @return array|string|NULL
     */
    public function getArgVal($index = NULL) {
        if(is_null($index)) {
            return $this->argsArr;
        } else if(isset($this->argsArr[$index])) {
            return $this->argsArr[$index];
        }
        return null;
    }

    /**
     * 是否是 -s 形式的短选项
     * @param string $opt
     * @return string|boolean 返回短选项名
     */
    private function isShortOptions($opt) {
        if(preg_match('/^\-([a-zA-Z0-9])$/', $opt, $matchs)) {
            return $matchs[1];
        }
        return false;
    }

    /**
     * 是否是 -svalue 形式的短选项
     * @param string $opt
     * @return array|boolean 返回短选项名以及选项值
     */
    private function isShortOptionsWithValue($opt) {
        if(preg_match('/^\-([a-zA-Z0-9])(\S+)$/', $opt, $matchs)) {
            return [$matchs[1], $matchs[2]];
        }
        return false;
    }

    /**
     * 是否是 --longopts 形式的长选项
     * @param string $opt
     * @return string|boolean 返回长选项名
     */
    private function isLongOptions($opt) {
        if(preg_match('/^\-\-([a-zA-Z0-9\-_]{2,})$/', $opt, $matchs)) {
            return $matchs[1];
        }
        return false;
    }

    /**
     * 是否是 --longopts=value 形式的长选项
     * @param string $opt
     * @return array|boolean 返回长选项名及选项值
     */
    private function isLongOptionsWithValue($opt) {
        if(preg_match('/^\-\-([a-zA-Z0-9\-_]{2,})(?:\=(.*?))$/', $opt, $matchs)) {
            return [$matchs[1], $matchs[2]];
        }
        return false;
    }

    /**
     * 是否是命令行参数
     * @param string $value
     * @return boolean
     */
    private function isArg($value) {
        return ! preg_match('/^\-/', $value);
    }

    /**
     * @param $argv
     * @return array ['opts'=>[], 'args'=>[]]
     */
    public final function parseArgs(array $argv)
    {
        $this->argsArr = [];
        $this->optsArr = [];
        $index = 1;
        $length = count($argv);
        while($index < $length) {
            $curVal = $argv[$index];
            if( ($key = $this->isShortOptions($curVal)) || ($key = $this->isLongOptions($curVal)) ) {
                $index++;
                if( isset($argv[$index]) && self::isArg($argv[$index]) ) {
                    $this->optsArr[$key] = $argv[$index];
                } else {
                    $this->optsArr[$key] = true;
                    $index--;
                }
            }
            else if( ($key = $this->isShortOptionsWithValue($curVal))
                || ($key = $this->isLongOptionsWithValue($curVal)) ) {
                $this->optsArr[$key[0]] = $key[1];
            }
            else if( self::isArg($curVal) ) {
                $this->argsArr[] = $curVal;
            }
            $index++;
        }
        $call = false;
        foreach ($this->optsArr as $option => $value){
            if(isset($this->optAutoCallback[$option])){
                call_user_func($this->optAutoCallback[$option],$value);
                $call = true;
            }
        }
        if(!$call && isset($this->optAutoCallback[self::OPT_DEFAULT_CALLBACK])){
            call_user_func($this->optAutoCallback[self::OPT_DEFAULT_CALLBACK],$this->optsArr,$this->argsArr);
        }

        $call = false;
        foreach ($this->argsArr as $value){
            if(isset($this->argAutoCallback[$value])){
               call_user_func($this->argAutoCallback[$value],$value);
               $call = true;
            }
        }
        if(!$call && isset($this->argAutoCallback[self::ARG_DEFAULT_CALLBACK])){
            call_user_func($this->argAutoCallback[self::ARG_DEFAULT_CALLBACK],$this->argsArr,$this->optsArr);
        }

        return ['opts'=>$this->optsArr, 'args'=>$this->argsArr];
    }
}
