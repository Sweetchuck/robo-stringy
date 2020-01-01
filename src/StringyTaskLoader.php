<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Stringy;

use Robo\Collection\CollectionBuilder;

trait StringyTaskLoader
{
    /**
     * @return \Sweetchuck\Robo\Stringy\Task\StringyTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskStringy(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\Stringy\Task\StringyTask $task */
        $task = $this->task(Task\StringyTask::class);
        $task->setOptions($options);

        return $task;
    }
}
