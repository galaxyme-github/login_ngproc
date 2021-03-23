<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MYQUOTES Model
 *
 * @method \App\Model\Entity\MYQUOTE get($primaryKey, $options = [])
 * @method \App\Model\Entity\MYQUOTE newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MYQUOTE[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MYQUOTE|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MYQUOTE saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MYQUOTE patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MYQUOTE[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MYQUOTE findOrCreate($search, callable $callback = null, $options = [])
 */
class MYQUOTESTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('m_y_q_u_o_t_e_s');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 100)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->decimal('budget_expectation')
            ->requirePresence('budget_expectation', 'create')
            ->notEmptyString('budget_expectation');

        $validator
            ->date('date_of_inclusion')
            ->requirePresence('date_of_inclusion', 'create')
            ->notEmptyDate('date_of_inclusion');

        $validator
            ->date('deadline_to_conclusion')
            ->requirePresence('deadline_to_conclusion', 'create')
            ->notEmptyDate('deadline_to_conclusion');

        $validator
            ->scalar('status')
            ->maxLength('status', 60)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        return $validator;
    }
}
