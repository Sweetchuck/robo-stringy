<?php

namespace Sweetchuck\Robo\Stringy\Test\Helper\RoboFiles;

use Robo\Collection\CollectionBuilder;
use Robo\State\Data as RoboStateData;
use Robo\Tasks;
use Sweetchuck\Robo\Stringy\StringyTaskLoader;
use Symfony\Component\Yaml\Yaml;

class StringyRoboFile extends Tasks
{
    use StringyTaskLoader;

    public function stringy(string $string): CollectionBuilder
    {
        return $this
            ->collectionBuilder()
            ->addTask(
                $this
                    ->taskStringy()
                    ->setString($string)
                    ->callSwapCase()
            )
            ->addCode(function (RoboStateData $data): int {
                $assets = $data->getArrayCopy();
                unset($assets['time']);

                $this
                    ->output()
                    ->write(Yaml::dump($assets, 42, 4));

                return 0;
            });
    }
}
