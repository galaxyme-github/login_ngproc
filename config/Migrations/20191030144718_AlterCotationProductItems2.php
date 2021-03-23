<?php
use Migrations\AbstractMigration;

class AlterCotationProductItems2 extends AbstractMigration
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
        $table = $this->table('cotation_product_items');
        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->addForeignKey('product_id', 'products', 'id');
        // $table->removeColumn('sku');
        // $table->removeColumn('item_name');
        // $table->removeColumn('model');
        // $table->removeColumn('category_item_prod');
        // $table->removeColumn('manufacturer');
        $table->update();
    }
}
