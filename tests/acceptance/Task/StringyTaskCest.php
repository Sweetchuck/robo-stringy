<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Stringy\Tests\Acceptance\Task;

use Sweetchuck\Robo\Stringy\Tests\AcceptanceTester;
use Sweetchuck\Robo\Stringy\Tests\Helper\RoboFiles\StringyRoboFile;

/**
 * @covers \Sweetchuck\Robo\Stringy\Task\StringyTask
 * @covers \Sweetchuck\Robo\Stringy\StringyTaskLoader
 */
class StringyTaskCest
{
    public function runStringy(AcceptanceTester $tester)
    {
        $id = 'stringy';
        $tester->runRoboTask($id, StringyRoboFile::class, 'stringy', 'fooBAR');
        $exitCode = $tester->getRoboTaskExitCode($id);
        $stdOutput = $tester->getRoboTaskStdOutput($id);
        $stdError = $tester->getRoboTaskStdError($id);

        $tester->assertSame(0, $exitCode);
        $tester->assertSame('stringy: FOObar' . PHP_EOL, $stdOutput);
        $tester->assertSame(' [Stringy] swapCase' . PHP_EOL, $stdError);
    }
}
