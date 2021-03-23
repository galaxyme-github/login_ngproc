<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CotationServices Model
 *
 * @property \App\Model\Table\CotationsTable&\Cake\ORM\Association\BelongsTo $Cotations
 *
 * @method \App\Model\Entity\CotationService get($primaryKey, $options = [])
 * @method \App\Model\Entity\CotationService newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CotationService[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CotationService|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CotationService saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CotationService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CotationService[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CotationService findOrCreate($search, callable $callback = null, $options = [])
 */
class CotationServicesTable extends Table
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

        $this->setTable('cotation_services');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Cotations', [
            'foreignKey' => 'cotation_id',
            'joinType' => 'INNER'
        ]);
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
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->scalar('service_time')
            ->maxLength('service_time', 255)
            ->requirePresence('service_time', 'create')
            ->notEmptyString('service_time');

        $validator
            ->scalar('category')
            ->maxLength('category', 255)
            ->requirePresence('category', 'create')
            ->notEmptyString('category');

        $validator
            ->scalar('collection_type')
            ->maxLength('collection_type', 255)
            ->requirePresence('collection_type', 'create')
            ->notEmptyString('collection_type');

        $validator
            ->scalar('expectation_start')
            ->maxLength('expectation_start', 255)
            ->requirePresence('expectation_start', 'create')
            ->notEmptyString('expectation_start');

        $validator
            ->numeric('estimate')
            ->requirePresence('estimate', 'create')
            ->notEmptyString('estimate');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['cotation_id'], 'Cotations'));

        return $rules;
    }
}
