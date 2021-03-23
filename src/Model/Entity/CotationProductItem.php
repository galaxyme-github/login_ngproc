<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CotationProductItem Entity
 *
 * @property int $id
 * @property int $cotation_product_id
 * @property int $product_id
 * @property int $quantity
 * @property float $quote
 * @property string|null $sku
 *
 * @property \App\Model\Entity\CotationProduct $cotation_product
 * @property \App\Model\Entity\Product $product
 */
class CotationProductItem extends Entity
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
        'cotation_product_id' => true,
        'product_id' => true,
        'quantity' => true,
        'quote' => true,
        'sku' => true,
        'cotation_product' => true,
        'product' => true,
        'provider_id' => true,
        'link_item' => true,
    ];
}
