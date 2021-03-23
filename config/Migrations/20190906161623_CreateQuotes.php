<?php
use Migrations\AbstractMigration;

class CreateQuotes extends AbstractMigration
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
        $table = $this->table('m_y_q_u_o_t_e_s');
        $table->addColumn('title', 'string', [ // titulo
            'default' => null,
            'limit' => 100,
            'null' => false,

        ]);
        $table->addColumn('budget_expectation', 'decimal', [ //espectativa de orçamento
            'default' => null,
            'limit' => 10.2,
            'null' => false,
        ]);
        $table->addColumn('date_of_inclusion', 'date', [ //data de inclusão
            'default' => null,
            'null' => false,
        ]);

        $table->addColumn('deadline_to_conclusion', 'date', [ // prazo de conclusão
            'default' => null,
            'null' => false,

        ]);

        $table->addColumn('status', 'string', [ // status
            'default' => null,
            'limit' => 60,
            'null' => false,

        ]);


       
        $table->create();
    }
}
