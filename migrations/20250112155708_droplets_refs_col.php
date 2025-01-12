<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DropletsRefsCol extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table_name = 'pipetic_droplets';
        $table = $this->table($table_name);
        $table->addColumn('ref', 'string', ['null' => true, 'after' => 'id']);
        $table->addIndex(['ref']);
        $table->addIndex(['source']);
        $table->addIndex(['destination']);
        $table->save();
    }
}
