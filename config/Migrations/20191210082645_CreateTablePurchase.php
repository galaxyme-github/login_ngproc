<?php
use Migrations\AbstractMigration;

class CreateTablePurchase extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('purchases');

        $table->addColumn('cotation_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('cotation_id', 'cotations', 'id');

        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('user_id', 'users', 'id');

        $table->addColumn('value', 'float', [
            'default' => null,
            'null' => false,
        ]);

        $table->addColumn('status', 'boolean', [
            'default' => null,
            'null' => false,
        ]);

        $table->create();
    }
}
