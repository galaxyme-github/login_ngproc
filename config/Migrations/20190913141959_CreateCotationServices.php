<?php
use Migrations\AbstractMigration;

class CreateCotationServices extends AbstractMigration
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
        $table = $this->table('cotation_services');
        $table->addColumn('description', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('service_time', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('category', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('collection_type', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('expectation_start', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
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
