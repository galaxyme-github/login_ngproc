<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CotationProduct Entity
 *
 * @property int $id
 * @property float $estimate
 * @property int $cotation_id
 *
 * @property \App\Model\Entity\Cotation $cotation
 * @property \App\Model\Entity\CotationProductItem[] $cotation_product_items
 */
class CotationProduct extends Entity
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
        'estimate' => true,
        'cotation_id' => true,
        'cotation' => true,
        'cotation_product_items' => true
    ];
}
