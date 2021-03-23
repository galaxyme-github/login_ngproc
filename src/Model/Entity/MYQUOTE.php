<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MYQUOTE Entity
 *
 * @property int $id
 * @property string $title
 * @property float $budget_expectation
 * @property \Cake\I18n\FrozenDate $date_of_inclusion
 * @property \Cake\I18n\FrozenDate $deadline_to_conclusion
 * @property string $status
 */
class MYQUOTE extends Entity
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
        'title' => true,
        'budget_expectation' => true,
        'date_of_inclusion' => true,
        'deadline_to_conclusion' => true,
        'status' => true
    ];
}
