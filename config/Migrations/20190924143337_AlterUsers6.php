<?php
use Migrations\AbstractMigration;

class AlterUsers6 extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('token', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('confirmed_cellphone', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
