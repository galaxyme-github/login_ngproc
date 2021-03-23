<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Person Entity
 *
 * @property int $user_id
 * @property \Cake\I18n\FrozenDate|null $birth_date
 * @property string|null $doc_cpf
 * @property string|null $doc_rg
 * @property string|null $telephone
 * @property string|null $cellphone
 * @property string|null $address_zipcode
 * @property string|null $address_street
 * @property string|null $address_number
 */
class Person extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'birth_date' => true,
        'doc_cpf' => true,
        'doc_rg' => true,
        'telephone' => true,
        'cellphone' => true,
        'address_zipcode' => true,
        'address_street' => true,
        'address_number' => true,
        'user' => true
    ];
}
