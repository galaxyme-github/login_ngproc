<?php
use Migrations\AbstractMigration;

class CotationsUsers extends AbstractMigration
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
        
        $table = $this->table('cotation_user');
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'null' => false,
            'limit' => 11,
        ]);
        $table->addColumn('cotation_id', 'integer', [
            'default' => null,
            'null' => false,
            'limit' => 11,
        ]);
        $table->addForeignKey('cotation_id', 'cotations', 'id');
        $table->addForeignKey('user_id', 'users', 'id');
        $table->create();
    }
}
