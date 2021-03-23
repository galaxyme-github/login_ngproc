<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Evaluation Entity
 *
 * @property int $id
 * @property int $parter_id
 * @property int $client_id
 * @property int $cotation_id
 * @property int $value
 * @property string $owner
 *
 * @property \App\Model\Entity\Cotation $cotation
 */
class Evaluation extends Entity
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
        'parter_id' => true,
        'client_id' => true,
        'cotation_id' => true,
        'value' => true,
        'owner' => true,
        'cotation' => true,
    ];
}
