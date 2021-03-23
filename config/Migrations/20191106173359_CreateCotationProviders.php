<?php
use Migrations\AbstractMigration;

class CreateCotationProviders extends AbstractMigration
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
        $table->addColumn('cotation_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('cotation_id', 'cotations', 'id');
        $table->addColumn('provider_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('provider_id', 'providers', 'id');
        $table->addColumn('deadline', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('cost', 'float', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
