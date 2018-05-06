<?php

namespace Sweetchuck\Robo\Stringy\Tests\Acceptance\Task;

use Sweetchuck\Robo\Stringy\Test\AcceptanceTester;
use Sweetchuck\Robo\Stringy\Test\Helper\RoboFiles\StringyRoboFile;

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
