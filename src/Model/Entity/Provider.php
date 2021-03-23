<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Provider Entity
 *
 * @property int $id
 * @property string $name
 * @property string $cnpj
 * @property string|null $site
 * @property string|null $telephone
 * @property string $address_zipcode
 * @property string $address_street
 * @property string $address_number
 * @property string|null $address_complement
 * @property string $address_neighborhood
 * @property string $address_city
 * @property string $address_uf
 *
 * @property \App\Model\Entity\CotationProvider[] $cotation_provider
 */
class Provider extends Entity
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
        'name' => true,
        'cnpj' => true,
        'site' => true,
        'telephone' => true,
        'address_zipcode' => true,
        'address_street' => true,
        'address_number' => true,
        'address_complement' => true,
        'address_neighborhood' => true,
        'address_city' => true,
        'address_uf' => true,
        'cotation_provider' => true,
        'created' => true,
        'active' => true
    ];
}
