<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\CotationsTable&\Cake\ORM\Association\HasMany $Cotations
 * @property \App\Model\Table\PersonsTable&\Cake\ORM\Association\HasMany $Persons
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Cotations', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Persons', [
            'foreignKey' => 'user_id'
        ]);

        $this->hasMany('Purchases', [
            'foreignKey' => 'cotation_id',
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
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('role')
            ->requirePresence('role', 'create')
            ->notEmptyString('role');

        $validator
            ->boolean('active')
            ->requirePresence('active', 'create')
            ->notEmptyString('active');

        $validator
            ->boolean('first_login')
            ->requirePresence('first_login', 'create')
            ->notEmptyString('first_login');

        $validator
            ->boolean('confirmed_email')
            ->requirePresence('confirmed_email', 'create')
            ->notEmptyString('confirmed_email');

        $validator
            ->scalar('english_level')
            ->maxLength('english_level', 255)
            ->allowEmptyString('english_level');

        $validator
            ->scalar('purchasing_exp')
            ->maxLength('purchasing_exp', 255)
            ->allowEmptyString('purchasing_exp');

        $validator
            ->scalar('acting_cat')
            ->maxLength('acting_cat', 255)
            ->allowEmptyString('acting_cat');

        $validator
            ->scalar('bank_username')
            ->maxLength('bank_username', 255)
            ->allowEmptyString('bank_username');

        $validator
            ->scalar('bank_cpf')
            ->maxLength('bank_cpf', 255)
            ->allowEmptyString('bank_cpf');

        $validator
            ->scalar('bank_agency')
            ->maxLength('bank_agency', 255)
            ->allowEmptyString('bank_agency');

        $validator
            ->scalar('bank_account')
            ->maxLength('bank_account', 255)
            ->allowEmptyString('bank_account');

        $validator
            ->scalar('bank_name')
            ->maxLength('bank_name', 255)
            ->allowEmptyString('bank_name');

        $validator
            ->integer('corporate')
            ->allowEmptyString('corporate');

        $validator
            ->date('birth_date')
            ->allowEmptyDate('birth_date');

        $validator
            ->scalar('doc_cpf')
            ->maxLength('doc_cpf', 255)
            ->allowEmptyString('doc_cpf');

        $validator
            ->scalar('doc_cnpj')
            ->maxLength('doc_cnpj', 255)
            ->allowEmptyString('doc_cnpj');

        $validator
            ->scalar('doc_rg')
            ->maxLength('doc_rg', 255)
            ->allowEmptyString('doc_rg');

        $validator
            ->scalar('telephone')
            ->maxLength('telephone', 255)
            ->allowEmptyString('telephone');

        $validator
            ->scalar('cellphone')
            ->maxLength('cellphone', 255)
            ->allowEmptyString('cellphone');

        $validator
            ->scalar('address_zipcode')
            ->maxLength('address_zipcode', 255)
            ->allowEmptyString('address_zipcode');

        $validator
            ->scalar('address_street')
            ->maxLength('address_street', 255)
            ->allowEmptyString('address_street');

        $validator
            ->scalar('address_number')
            ->maxLength('address_number', 255)
            ->allowEmptyString('address_number');

        $validator
            ->scalar('address_complement')
            ->maxLength('address_complement', 255)
            ->allowEmptyString('address_complement');

        $validator
            ->scalar('address_city')
            ->maxLength('address_city', 255)
            ->allowEmptyString('address_city');

        $validator
            ->scalar('address_uf')
            ->maxLength('address_uf', 255)
            ->allowEmptyString('address_uf');

        $validator
            ->scalar('address_neighborhood')
            ->maxLength('address_neighborhood', 255)
            ->allowEmptyString('address_neighborhood');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->allowEmptyString('token');

        $validator
        ->scalar('img_profile')
        ->maxLength('img_profile', 255)
        ->allowEmptyString('img_profile');

        $validator
            ->boolean('confirmed_cellphone')
            ->allowEmptyString('confirmed_cellphone');

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
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
