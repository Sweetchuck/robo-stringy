<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\Stringy\Tests\Unit\Task;

use Codeception\Test\Unit;
use League\Container\Container as LeagueContainer;
use Robo\Application as RoboApplication;
use Robo\Collection\CollectionBuilder;
use Robo\Config\Config;
use Robo\Robo;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyOutput;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;
use Sweetchuck\Robo\Stringy\Tests\Helper\Dummy\DummyTaskBuilder;
use Sweetchuck\Robo\Stringy\Tests\UnitTester;
use Symfony\Component\ErrorHandler\BufferingLogger;

abstract class TaskTestBase extends Unit
{

    /**
     * @var \League\Container\ContainerInterface
     */
    protected $container;

    protected Config $config;

    protected CollectionBuilder $builder;

    protected UnitTester $tester;

    /**
     * @var \Sweetchuck\Robo\Stringy\Task\StringyTask|\Robo\Collection\CollectionBuilder
     */
    protected $task;

    protected DummyTaskBuilder $taskBuilder;

    /**
     * @inheritdoc
     */
    public function _before()
    {
        parent::_before();

        Robo::unsetContainer();
        DummyProcess::reset();

        $this->container = new LeagueContainer();
        $application = new RoboApplication('Sweetchuck - Robo Stringy', '1.0.0');
        $this->config = new Config();
        $input = null;
        $output = new DummyOutput([
            'verbosity' => DummyOutput::VERBOSITY_DEBUG,
        ]);

        $this->container->add('container', $this->container);

        Robo::configureContainer($this->container, $application, $this->config, $input, $output);
        $this->container->share('logger', BufferingLogger::class);

        $this->builder = CollectionBuilder::create($this->container, null);
        $this->taskBuilder = new DummyTaskBuilder();
        $this->taskBuilder->setContainer($this->container);
        $this->taskBuilder->setBuilder($this->builder);

        $this->initTask();
    }

    /**
     * @return $this
     */
    abstract protected function initTask();
}
