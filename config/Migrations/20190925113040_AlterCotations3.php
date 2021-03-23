<?php
use Migrations\AbstractMigration;

class AlterCotations3 extends AbstractMigration
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
        $table = $this->table('cotations');
        //$table->renameColumn('cotation_type', 'type');
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'null' => true,
            'limit' => 11,
        ]);
        $table->addForeignKey('user_id', 'users', 'id');
        $table->update();
    }
}
