<?php
declare(strict_types=1);
/**
 * This file is part of EasySwoole.
 *
 * @link https://www.easyswoole.com
 * @document https://www.easyswoole.com
 * @contact https://www.easyswoole.com/Preface/contact.html
 * @license https://github.com/easy-swoole/easyswoole/blob/3.x/LICENSE
 */

namespace EasySwoole\Utility\Tests;

use EasySwoole\Utility\Time;
use PHPUnit\Framework\TestCase;

class TimeTest extends TestCase
{
    public function testStartTimestamp()
    {
        // 传入文本日期
        $startDate = '2021-10-05';
        $startDateTime = $startDate . ' 01:02:03';
        $startTimestamp = Time::startTimestamp($startDateTime);
        $startTimestamp2DateTime = date("Y-m-d H:i:s", $startTimestamp);
        $this->assertSame($startTimestamp2DateTime, $startDate . ' 00:00:00');

        // 传入时间戳
        $startDateTimestamp = strtotime('2021-10-05 01:02:03');
        $startTimestamp = Time::startTimestamp($startDateTimestamp);
        $startTimestamp2DateTime = date("Y-m-d H:i:s", $startTimestamp);
        $this->assertSame($startTimestamp2DateTime, $startDate . ' 00:00:00');
    }

    public function testEndTimestamp()
    {
        // 传入文本日期
        $endDate = '2021-10-05';
        $endDateTime = $endDate . ' 01:02:03';
        $endTimestamp = Time::endTimestamp($endDateTime);
        $endTimestamp2DateTime = date("Y-m-d H:i:s", $endTimestamp);
        $this->assertSame($endTimestamp2DateTime, $endDate . ' 23:59:59');

        // 传入时间戳
        $endDateTimestamp = strtotime('2021-10-05 01:02:03');
        $endTimestamp = Time::endTimestamp($endDateTimestamp);
        $endTimestamp2DateTime = date("Y-m-d H:i:s", $endTimestamp);
        $this->assertSame($endTimestamp2DateTime, $endDate . ' 23:59:59');
    }

    public function testCreateDateTimeClass()
    {
        // 传入文本日期
        $dateStr = '2021-10-05 01:02:03';
        $dateStr2timestamp = strtotime($dateStr);
        $dateTimeClass = Time::createDateTimeClass($dateStr);
        $this->assertInstanceOf(\DateTime::class, $dateTimeClass);
        $this->assertSame($dateStr2timestamp, $dateTimeClass->getTimestamp());
        $dateTimezone = $dateTimeClass->getTimezone();
        // 当前时区信息
        $nowTimezone = new \DateTimeZone(date_default_timezone_get());
        $nowTimezoneName = $nowTimezone->getName();
        // 断言当前时区
        $this->assertInstanceOf(\DateTimeZone::class, $dateTimezone);
        $this->assertSame($nowTimezoneName, $dateTimezone->getName());

        // 传入时间戳
        $timestamp = strtotime('2021-10-05 01:02:03');
        $dateTimeClass = Time::createDateTimeClass($timestamp);
        $this->assertInstanceOf(\DateTime::class, $dateTimeClass);
        $this->assertSame($dateStr2timestamp, $dateTimeClass->getTimestamp());
        $dateTimezone = $dateTimeClass->getTimezone();
        // 当前时区信息
        $nowTimezone = new \DateTimeZone(date_default_timezone_get());
        $nowTimezoneName = $nowTimezone->getName();
        // 断言当前时区
        $this->assertInstanceOf(\DateTimeZone::class, $dateTimezone);
        $this->assertSame($nowTimezoneName, $dateTimezone->getName());
    }

    public function testParserDateTime()
    {
        // 传入文本日期
        $dateStr = '2022-01-13 01:02:03';
        $dateTimeArray = Time::parserDateTime($dateStr);
        $this->assertSame('01', $dateTimeArray[0]);
        $this->assertSame('02', $dateTimeArray[1]);
        $this->assertSame('03', $dateTimeArray[2]);
        $this->assertSame('1', $dateTimeArray[3]);
        $this->assertSame('13', $dateTimeArray[4]);
        $this->assertSame('2022', $dateTimeArray[5]);

        // 传入时间戳
        $timestamp = strtotime('2022-01-13 01:02:03');
        $dateTimeArray = Time::parserDateTime($timestamp);
        $this->assertSame('01', $dateTimeArray[0]);
        $this->assertSame('02', $dateTimeArray[1]);
        $this->assertSame('03', $dateTimeArray[2]);
        $this->assertSame('1', $dateTimeArray[3]);
        $this->assertSame('13', $dateTimeArray[4]);
        $this->assertSame('2022', $dateTimeArray[5]);
    }
}
