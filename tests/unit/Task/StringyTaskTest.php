<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Stringy\Tests\Unit\Task;

/**
 * @covers \Sweetchuck\Robo\Stringy\Task\StringyTask
 * @covers \Sweetchuck\Robo\Stringy\StringyTaskLoader
 */
class StringyTaskTest extends TaskTestBase
{

    /**
     * @inheritdoc
     */
    protected function initTask()
    {
        $this->task = $this->taskBuilder->taskStringy();

        return $this;
    }

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
        $result = $this
            ->task
            ->setOptions($options)
            ->run();

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
        $this->tester->assertFalse(method_exists($this->task, 'callIsUpperCase'));

        $result = $this
            ->task
            ->setString('UPPER')
            ->callIsUpperCase()
            ->run();

        $this->tester->assertTrue($result['stringy.isUpperCase']);

        $this->tester->assertSame(
            'UPPER',
            $result['stringy']
        );
    }

    public function testMagicMethodCallStartsWithCallFail(): void
    {
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage("Stringy has no callable method: 'notExists'");
        $this->task->callNotExists();
    }

    public function testMagicMethodCallTotallyWrong(): void
    {
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage("Method 'fooBar' does not exists");
        $this->task->fooBar();
    }

    /**
     * Non existing Stringy method added as queue item.
     */
    public function testRunMethodNotExists(): void
    {
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage("Stringy has no callable method: 'notExists'");
        $this
            ->task
            ->addToQueue(['method' => 'notExists'])
            ->run();
    }
}
