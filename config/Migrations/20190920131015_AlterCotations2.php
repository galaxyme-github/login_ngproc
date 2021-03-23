<?php
use Migrations\AbstractMigration;

class AlterCotations2 extends AbstractMigration
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
        /*$table->changeColumn('type', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);*/
        $table->update();
    }
}
