<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CotationProducts Model
 *
 * @property \App\Model\Table\CotationsTable&\Cake\ORM\Association\BelongsTo $Cotations
 * @property \App\Model\Table\CotationProductItemsTable&\Cake\ORM\Association\HasMany $CotationProductItems
 *
 * @method \App\Model\Entity\CotationProduct get($primaryKey, $options = [])
 * @method \App\Model\Entity\CotationProduct newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CotationProduct[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CotationProduct|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CotationProduct saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CotationProduct patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CotationProduct[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CotationProduct findOrCreate($search, callable $callback = null, $options = [])
 */
class CotationProductsTable extends Table
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

        $this->setTable('cotation_products');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Cotations', [
            'foreignKey' => 'cotation_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('CotationProductItems', [
            'foreignKey' => 'cotation_product_id'
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
