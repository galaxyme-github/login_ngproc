<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CotationAttachmentsTable Model
 *
 * @method \App\Model\Entity\CotationAttachmentsTable get($primaryKey, $options = [])
 * @method \App\Model\Entity\CotationAttachmentsTable newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CotationAttachmentsTable[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CotationAttachmentsTable|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CotationAttachmentsTable saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CotationAttachmentsTable patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CotationAttachmentsTable[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CotationAttachmentsTable findOrCreate($search, callable $callback = null, $options = [])
 */
class CotationAttachmentsTable extends Table
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

        $this->setTable('cotation_attachments');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Cotations', [
            'foreignKey' => 'cotation_id',
            'joinType' => 'INNER',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

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
        $rules->add($rules->existsIn(['id_cotation'], 'Cotations'));

        return $rules;
    }
}
