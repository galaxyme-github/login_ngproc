<?php
use Migrations\AbstractMigration;

class CreateCotationProducts extends AbstractMigration
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
        $table = $this->table('cotation_products');
        $table->addColumn('estimate', 'float', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('cotation_id', 'integer', [
            'default' => null,
            'null' => false,
            'limit' => 11,
        ]);
        $table->addForeignKey('cotation_id', 'cotations', 'id');
        $table->create();
    }
}
