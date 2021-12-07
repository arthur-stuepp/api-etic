<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Event extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('event');
        $table->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('name', 'string')
            ->addColumn('type', 'integer')
            ->addColumn('description', 'string')
            ->addColumn('capacity', 'integer')
            ->addColumn('start_time', 'timestamp')
            ->addColumn('end_time', 'timestamp')
            ->addIndex('name', ['unique' => true])
            ->create();
        $table = $this->table('event_user');
        $table->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('user', 'integer')
            ->addColumn('event', 'integer')
            ->addColumn('team', 'string', ['null' => true])
            ->addColumn('waitlist', 'boolean')
            ->addColumn('cheking', 'boolean')
            ->addIndex(['user', 'event'], ['unique' => true])
            ->addForeignKey('user', 'user', 'id')
            ->addForeignKey('event', 'event', 'id')
            ->create();
    }
}
