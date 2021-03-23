<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Providers Model
 *
 * @property \App\Model\Table\CotationProviderTable&\Cake\ORM\Association\HasMany $CotationProvider
 *
 * @method \App\Model\Entity\Provider get($primaryKey, $options = [])
 * @method \App\Model\Entity\Provider newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Provider[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Provider|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Provider saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Provider patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Provider[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Provider findOrCreate($search, callable $callback = null, $options = [])
 */
class ProvidersTable extends Table
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

        $this->addBehavior('Timestamp');
        
        $this->setTable('providers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('CotationProvider', [
            'foreignKey' => 'provider_id'
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

        $validator
            ->scalar('cnpj')
            ->maxLength('cnpj', 255)
            ->requirePresence('cnpj', 'create')
            ->notEmptyString('cnpj');

        $validator
            ->scalar('site')
            ->maxLength('site', 255)
            ->allowEmptyString('site');

        $validator
            ->scalar('telephone')
            ->maxLength('telephone', 255)
            ->allowEmptyString('telephone');

        $validator
            ->scalar('address_zipcode')
            ->maxLength('address_zipcode', 255)
            ->requirePresence('address_zipcode', 'create')
            ->notEmptyString('address_zipcode');

        $validator
            ->scalar('address_street')
            ->maxLength('address_street', 255)
            ->requirePresence('address_street', 'create')
            ->notEmptyString('address_street');

        $validator
            ->scalar('address_number')
            ->maxLength('address_number', 255)
            ->requirePresence('address_number', 'create')
            ->notEmptyString('address_number');

        $validator
            ->scalar('address_complement')
            ->maxLength('address_complement', 255)
            ->allowEmptyString('address_complement');

        $validator
            ->scalar('address_neighborhood')
            ->maxLength('address_neighborhood', 255)
            ->requirePresence('address_neighborhood', 'create')
            ->notEmptyString('address_neighborhood');

        $validator
            ->scalar('address_city')
            ->maxLength('address_city', 255)
            ->requirePresence('address_city', 'create')
            ->notEmptyString('address_city');

        $validator
            ->scalar('address_uf')
            ->maxLength('address_uf', 255)
            ->requirePresence('address_uf', 'create')
            ->notEmptyString('address_uf');

        return $validator;
    }
}
