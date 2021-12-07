<?php /** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class School extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('school');
        $table->addColumn('name', 'string')
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->insert([
                [
                    'id' => 1,
                    'name' => 'IFC'
                ],
                [
                    'id' => 2,
                    'name' => 'UNIVALI'
                ],
            ])
            ->create();
    }
}
