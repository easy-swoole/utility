<?php

namespace EasySwoole\Utility;

class PhpBloomFilter
{
    private string $bitmap;
    private int $bitSize;
    private int $hashCount;

    public function __construct(int $bitSize = 1024*1024,int $hashCount = 5)
    {
        $this->bitSize = $bitSize;
        $this->hashCount = $hashCount;
        $this->bitmap = str_repeat(chr(0), ceil($bitSize / 8));
    }

    private function getOffsets($string) {
        $offsets = [];
        // 经典的快速哈希算法，或者用 crc32(md5())
        for ($i = 0; $i < $this->hashCount; $i++) {
            $offsets[] = abs(crc32(md5($string . $i))) % $this->bitSize;
        }
        return $offsets;
    }

    public function add($string) {
        foreach ($this->getOffsets($string) as $offset) {
            $byteIndex = (int)($offset / 8);
            $bitIndex = $offset % 8;
            $this->bitmap[$byteIndex] = chr(ord($this->bitmap[$byteIndex]) | (1 << $bitIndex));
        }
    }

    public function exists($string): bool {
        foreach ($this->getOffsets($string) as $offset) {
            $byteIndex = (int)($offset / 8);
            $bitIndex = $offset % 8;
            if ((ord($this->bitmap[$byteIndex]) & (1 << $bitIndex)) === 0) {
                return false; // 绝对不存在
            }
        }
        return true; // 可能存在
    }

}