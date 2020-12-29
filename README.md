
# robo-stringy

[![CircleCI](https://circleci.com/gh/Sweetchuck/robo-stringy.svg?style=svg)](https://circleci.com/gh/Sweetchuck/robo-stringy)
[![codecov](https://codecov.io/gh/Sweetchuck/robo-stringy/branch/1.x/graph/badge.svg)](https://codecov.io/gh/Sweetchuck/robo-stringy)

This Robo task is useful when you need to do string manipulation in a
`\Robo\State\Data`.


## Install

`composer require sweetchuck/robo-stringy`


## Task - taskStringy()

```php
<?php

class RoboFile extends \Robo\Tasks
{
    use \Sweetchuck\Robo\Stringy\StringyTaskLoader;
    
    /**
     * @command stringy:simple
     */
    public function cmdStringySimple(string $text = 'Hello', string $suffix = 'World')
    {
        return $this
            ->collectionBuilder()
            ->addTask(
                $this
                    ->taskStringy()
                    ->setString($text)
                    ->callIsUpperCase()
                    ->callAppend(" $suffix")
                    ->callUnderscored()
            )
            ->addCode(function (\Robo\State\Data $data): int {
                $output = $this->output();
                $output->writeln('Is upper case: ' . var_export($data['stringy.isUpperCase'], true));
                $output->writeln("Result: {$data['stringy']}");

                return 0;
            });
    }
}
```

Run `vendor/bin/robo stringy:simple`  
Output:
> <pre>Is upper case: false
> Result: hello_world</pre>

Run `vendor/bin/robo stringy:simple FOO`  
Output:
> <pre>Is upper case: true
> Result: f_o_o_world</pre>

