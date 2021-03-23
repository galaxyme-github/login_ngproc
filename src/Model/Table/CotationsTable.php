<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Cotations Model
 *
 * @property \App\Model\Table\CotationProductsTable&\Cake\ORM\Association\HasMany $CotationProducts
 * @property \App\Model\Table\CotationServicesTable&\Cake\ORM\Association\HasMany $CotationServices
 *
 * @method \App\Model\Entity\Cotation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cotation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Cotation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cotation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cotation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cotation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cotation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cotation findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CotationsTable extends Table
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

        $this->setTable('cotations');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('CotationProducts', [
            'foreignKey' => 'cotation_id',
        ]);
        $this->hasOne('CotationServices', [
            'foreignKey' => 'cotation_id',
        ]);
        $this->hasMany('CotationAttachments', [            
            'foreignKey' => 'cotation_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('CotationProviders', [
            'foreignKey' => 'cotation_id',
        ]);

        $this->hasOne('Purchases', [
            'foreignKey' => 'cotation_id',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->integer('provider_qtd')
            ->requirePresence('provider_qtd', 'create')
            ->notEmptyString('provider_qtd');

        $validator
            ->scalar('objective')
            ->maxLength('objective', 255)
            ->requirePresence('objective', 'create')
            ->notEmptyString('objective');

        $validator
            ->scalar('deadline_date')
            ->maxLength('deadline_date', 255)
            ->requirePresence('deadline_date', 'create')
            ->notEmptyString('deadline_date');

        $validator
            ->scalar('status')
            ->maxLength('status', 255)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->scalar('coverage')
            ->maxLength('coverage', 255)
            ->requirePresence('coverage', 'create')
            ->notEmptyString('coverage');

        return $validator;
    }
}
