<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Address extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('state');
        $table->addColumn('code', 'string', ['limit' => 2])
            ->addColumn('name', 'string')
            ->create();

        $table = $this->table('city');
        $table->addColumn('name', 'string')
            ->addColumn('state', 'integer')
            ->addForeignKey('state', 'state', 'id')
            ->create();

        $this->execute(file_get_contents(__DIR__ . '/../sql/state.sql'));
        $this->execute(file_get_contents(__DIR__ . '/../sql/city.sql'));
    }
}
