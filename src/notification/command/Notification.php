<?php

namespace yzh52521\notification\command;

use think\console\command\Make;

class Notification extends Make
{
    protected $type = "Notification";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:notification')
            ->setDescription('Create a new notification class');
    }

    protected function getStub(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR;
    }

    protected function getNamespace(string $app): string
    {
        return parent::getNamespace($app) . '\\notifications';
    }
}
