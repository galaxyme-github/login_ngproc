<?php
use Migrations\AbstractMigration;

class AlterAttachmentCotations extends AbstractMigration
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
        $table = $this->table('attachment_cotation');
        $table->rename('cotation_attachments');
        $table->renameColumn('id_cotation', 'cotation_id');
        $table->update();
    }
}
