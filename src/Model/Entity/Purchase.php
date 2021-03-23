<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property string $item_name
 * @property string $model
 * @property string $category_item_prod
 * @property string $manufacturer
 *
 * @property \App\Model\Entity\CotationProductItem[] $cotation_product_items
 */
class Purchase extends Entity
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
        'id' => true,
        'value' => true,
        'user_id' => true,
        'cotation_id' => true,
        'status' => true,
        'payment_date' => true,
        'code_transaction' => true,
        'code_notification' => true,
        'commission_pay' => true,
        'discounted' => true,
    ];

}
