<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SendGrids Model
 *
 * @method \App\Model\Entity\SendGrid get($primaryKey, $options = [])
 * @method \App\Model\Entity\SendGrid newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SendGrid[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SendGrid|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SendGrid saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SendGrid patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SendGrid[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SendGrid findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SendGridsTable extends Table
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

        $this->setTable('send_grids');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->requirePresence('category', 'create')
            ->notEmptyString('category');

        $validator
            ->boolean('send')
            ->requirePresence('send', 'create')
            ->notEmptyString('send');

        return $validator;
    }
}
