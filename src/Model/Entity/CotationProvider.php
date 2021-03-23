<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CotationProvider Entity
 *
 * @property int $id
 * @property int $cotation_id
 * @property int $provider_id
 * @property \Cake\I18n\FrozenDate $deadline
 * @property float $cost
 *
 * @property \App\Model\Entity\Cotation $cotation
 * @property \App\Model\Entity\Provider $provider
 */
class CotationProvider extends Entity
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
        'cotation_id' => true,
        'provider_id' => true,
        'user_id' => true,
        'deadline' => true,
        'cost' => true,
        'cotation' => true,
        'provider' => true
    ];
}
