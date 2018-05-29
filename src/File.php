<?php

namespace EasySwoole\Utility;

/**
 * 文件助手类
 * Class File
 * @author  : evalor <master@evalor.cn>
 * @package EasySwoole\Utility
 */
class File
{
    /**
     * 遍历目录
     * @param string $dirPath
     * @return array|bool
     * @author : evalor <master@evalor.cn>
     */
    static function scanDirectory($dirPath)
    {
        if (!is_dir($dirPath)) return false;
        $dirPath = realpath($dirPath) . '/';
        $dirs = array( $dirPath );

        $fileContainer = array();
        $dirContainer = array();

        do {
            $workDir = array_pop($dirs);
            $scanResult = scandir($workDir);
            foreach ($scanResult as $files) {
                if ($files == '.' || $files == '..') continue;
                $realPath = $workDir . $files;
                if (is_dir($realPath)) {
                    array_push($dirs, $realPath . '/');
                    $dirContainer[] = $realPath;
                } elseif (is_file($realPath)) {
                    $fileContainer[] = $realPath;
                }
            }
        } while ($dirs);

        return [ 'files' => $fileContainer, 'dirs' => $dirContainer ];
    }
}