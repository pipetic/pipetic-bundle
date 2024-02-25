<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class PipeticDropsTable extends AbstractMigration
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
        $exists = $this->hasTable($table_name);
        if ($exists) {
            return;
        }
        $table = $this->table($table_name);
        $table
            ->addColumn('source', 'string', ['null' => true])
            ->addColumn('destination', 'string', ['null' => true])
            ->addColumn('subject_id', 'integer', ['null' => true])
            ->addColumn('subject', 'string', ['null' => true])
            ->addColumn('tries', 'integer', ['null' => true, 'limit' => MysqlAdapter::INT_TINY])
            ->addColumn('status', 'enum', ['null' => true, 'values' => ['pending', 'retying', 'failed', 'sent']])
            ->addColumn('metadata', 'json', ['null' => true])
            ->addColumn('issued_at', 'date', ['null' => true])
            ->addColumn('updated_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('created_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
            ]);


        $table
            ->addIndex(['subject_id'])
            ->addIndex(['subject']);

        $table->save();
    }
}
