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
                        'my.stringy.result' => 9,
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
                    ],
                ],
            ],
            'git commit-msg filter' => [
                [
                    'assets' => [
                        'stringy.result' => implode(PHP_EOL, [
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

    public function testMagickMethodCall(): void
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
            $result['stringy.result']
        );
    }
}
