<?php

namespace EasySwoole\Utility;

/**
 * 终端控制
 * Class Console
 * @author  : evalor <master@evalor.cn>
 * @package eValor
 */
class Console
{
    const E_RESET     = '0';  // 重置所有效果
    const E_LIGHT     = '1';  // 明亮
    const E_DARK      = '2';  // 昏暗
    const E_UNDERLINE = '4';  // 下划线
    const E_BLINK     = '5';  // 闪烁
    const E_REVERSE   = '7';  // 翻转
    const E_HIDDEN    = '8';  // 隐藏

    const F_BLACK   = '30;';   // 黑色
    const F_RED     = '31;';   // 红色
    const F_GREEN   = '32;';   // 绿色
    const F_YELLOW  = '33;';   // 黄色
    const F_BLUE    = '34;';   // 蓝色
    const F_CARMINE = '35;';   // 洋红
    const F_CYAN    = '36;';   // 青色
    const F_WHITE   = '37;';   // 白色

    const B_BLACK   = '40;';   // 黑色
    const B_RED     = '41;';   // 红色
    const B_GREEN   = '42;';   // 绿色
    const B_YELLOW  = '43;';   // 黄色
    const B_BLUE    = '44;';   // 蓝色
    const B_CARMINE = '45;';   // 洋红
    const B_CYAN    = '46;';   // 青色
    const B_WHITE   = '47;';   // 白色

    /**
     * 重置命令行界面
     * @author : evalor <master@evalor.cn>
     */
    static function terminalReset()
    {
        echo "\ec";
    }

    /**
     * 强制光标位置 不传参数回到左上角
     * @param int $row    行号
     * @param int $column 列号
     * @author : evalor <master@evalor.cn>
     */
    static function cursorHome($row = 0, $column = 0)
    {
        echo "\e[{$row};{$column}H";
    }

    /**
     * 光标上移
     * @param int $count 行数 默认1
     * @author : evalor <master@evalor.cn>
     */
    static function cursorUpward($count = 1)
    {
        echo "\e[{$count}A";
    }

    /**
     * 光标下移
     * @param int $count 行数 默认1
     * @author : evalor <master@evalor.cn>
     */
    static function cursorDownward($count = 1)
    {
        echo "\e[{$count}B";
    }

    /**
     * 光标左移
     * @param int $count 行数 默认1
     * @author : evalor <master@evalor.cn>
     */
    static function cursorForward($count = 1)
    {
        echo "\e[{$count}C";
    }

    /**
     * 光标右移
     * @param int $count 行数 默认1
     * @author : evalor <master@evalor.cn>
     */
    static function cursorBackward($count = 1)
    {
        echo "\e[{$count}D";
    }

    /**
     * 强制光标位置
     * @param int $row    行号
     * @param int $column 列号
     * @author : evalor <master@evalor.cn>
     */
    static function cursorForce($row = 0, $column = 0)
    {
        echo "\e[{$row};{$column}f";
    }

    /**
     * 从当前位置擦除到行首
     * @author : evalor <master@evalor.cn>
     */
    static function erasureStart()
    {
        echo "\e[1K";
    }

    /**
     * 从当前位置擦除到行尾
     * @author : evalor <master@evalor.cn>
     */
    static function erasureEnd()
    {
        echo "\e[K";
    }

    /**
     * 擦除当前行
     * @author : evalor <master@evalor.cn>
     */
    static function erasureLine()
    {
        echo "\e[2K";
    }

    /**
     * 从顶部擦除到底部
     * @author : evalor <master@evalor.cn>
     */
    static function erasureToBottom()
    {
        echo "\e[J";
    }

    /**
     * 从底部擦除到顶部
     * @author : evalor <master@evalor.cn>
     */
    static function erasureToHead()
    {
        echo "\e[1J";
    }

    /**
     * 擦除所有并移动到下一行
     * @author : evalor <master@evalor.cn>
     */
    static function erasureToNewLine()
    {
        echo "\e[2J";
    }

    /**
     * 创建多个空行
     * @param int $line
     * @author : evalor <master@evalor.cn>
     */
    static function newline($line = 1)
    {
        echo str_repeat("\n", $line);
    }

    /**
     * 输出彩色文字
     * @param string $content
     * @param string $frontColor
     * @param string $backColor
     * @param string $effect
     * @author : evalor <master@evalor.cn>
     */
    static function color($content = '', $frontColor = '', $backColor = '', $effect = '')
    {
        $colors = "\e[{$frontColor}{$backColor}{$effect}";
        $colors = rtrim($colors, ';') . 'm';
        echo "{$colors}{$content}\e[0m";
    }
}