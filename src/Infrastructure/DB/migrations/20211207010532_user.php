<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class User extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('user');
        $table->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('name', 'string')
            ->addColumn('type', 'integer')
            ->addColumn('email', 'string')
            ->addColumn('document', 'string', ['limit' => 14])
            ->addColumn('address', 'string')
            ->addColumn('birthday', 'datetime')
            ->addColumn('disability', 'boolean')
            ->addColumn('compamy', 'string', ['null' => true])
            ->addColumn('indication', 'integer', ['null' => true])
            ->addColumn('school', 'integer', ['null' => true])
            ->addColumn('city', 'integer')
            ->addIndex('email', ['unique' => true])
            ->addForeignKey('school', 'school', 'id')
            ->addForeignKey('city', 'city', 'id')
            ->addForeignKey('indication', 'user', 'id')
            ->create();
    }
}
