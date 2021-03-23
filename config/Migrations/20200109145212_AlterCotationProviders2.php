<?php
use Migrations\AbstractMigration;

class AlterCotationProviders2 extends AbstractMigration
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
        $table = $this->table('cotation_providers');
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'null' => false,
            'limit' => 11,
        ]);
        $table->addForeignKey('user_id', 'users', 'id');
        $table->update();
    }
}
