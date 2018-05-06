<?php

namespace Sweetchuck\Robo\Stringy\Tests\Unit\Task;

use Codeception\Test\Unit;
use Sweetchuck\Robo\Stringy\Task\StringyTask;

class StringyTaskTest extends Unit
{
    /**
     * @var \Sweetchuck\Robo\Stringy\Test\UnitTester
     */
    protected $tester;

    public function casesRun(): array
    {
        return [
            'basic' => [
                [
                    'assets' => [
                        'my.camelCase' => 'fooBarBaz',
                        'my.stringy.length' => 9,
                        'my.stringy.isUpperCase' => false,
                        'my.stringy' => 'prefix.fooBarBaz',
                    ],
                ],
                [
                    'assetNamePrefix' => 'my.',
                    'string' => 'foo-bar-baz',
                    'queue' => [
                        'underscored',
                        [
                            'method' => 'camelize',
                            'assetName' => 'camelCase',
                        ],
                        'length',
                        'isUpperCase',
                        [
                            'method' => 'prepend',
                            'args' => ['prefix.'],
                        ],
                    ],
                ],
            ],
            'git commit-msg filter' => [
                [
                    'assets' => [
                        'stringy' => implode(PHP_EOL, [
                            'Subject',
                            '',
                            'Long',
                            'body.',
                        ]),
                    ],
                ],
                [
                    'string' => implode(PHP_EOL, [
                        'Subject',
                        '',
                        'Long',
                        'body.',
                        '# a',
                        '# b',
                        '# c',
                        ''
                    ]),
                    'queue' => [
                        [
                            'method' => 'regexReplace',
                            'args' => [
                                '(^|(\r\n)|(\n\r)|\r|\n)#([^\r\n]*)|$',
                                '',
                            ],
                        ],
                        'trim',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesRun
     */
    public function testRun(array $expected, array $options): void
    {
        $task = new StringyTask();
        $task->setOptions($options);

        $result = $task->run();

        if (array_key_exists('assets', $expected)) {
            foreach ($expected['assets'] as $assetName => $assetValue) {
                $this->tester->assertSame(
                    $assetValue,
                    $result[$assetName],
                    "Asset '$assetName'"
                );
            }
        }
    }

    public function testMagickMethodCallStartsWithCallSuccess(): void
    {
        $task = new StringyTask();
        $this->tester->assertSame(
            false,
            method_exists($task, 'callIsUpperCase')
        );

        $result = $task
            ->setString('UPPER')
            ->callIsUpperCase()
            ->run();

        $this->tester->assertSame(
            true,
            $result['stringy.isUpperCase']
        );

        $this->tester->assertSame(
            'UPPER',
            $result['stringy']
        );
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionCode 1
     * @expectedExceptionMessage Stringy has no callable method: 'notExists'
     */
    public function testMagicMethodCallStartsWithCallFail(): void
    {
        (new StringyTask())->callNotExists();
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionCode 1
     * @expectedExceptionMessage Method 'fooBar' does not exists
     */
    public function testMagicMethodCallTotallyWrong(): void
    {
        (new StringyTask())->fooBar();
    }

    /**
     * Non existing Stringy method added as queue item.
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionCode 1
     * @expectedExceptionMessage Stringy has no callable method: 'notExists'
     */
    public function testRunMethodNotExists(): void
    {
        (new StringyTask())
            ->addToQueue(['method' => 'notExists'])
            ->run();
    }
}
