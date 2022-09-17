<?php

namespace yzh52521\notification\command;

use think\console\Command;
use think\helper\Str;
use think\migration\Creator;

class NotificationTable extends Command
{
    protected function configure()
    {
        $this->setName('notification:table')->setDescription("Create a migration for the notification table");
    }

    public function handle()
    {
        if (!$this->app->has('migration.creator')) {
            $this->output->error('Install think-migration first please');
            return;
        }

        $className = Str::studly("create_notification_table");
        /** @var Creator $creator */
        $creator = $this->app->get('migration.creator');
        $path    = $creator->create($className);
        // Load the alternative template if it is defined.
        $contents = file_get_contents(__DIR__ . '/stubs/notification.stub');

        file_put_contents($path, $contents);
        $this->output->info('Migration created successfully!');
    }

}
