<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property int $role
 * @property bool $active
 * @property bool $first_login
 * @property bool $confirmed_email
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class User extends Entity
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
        'email' => true,
        'password' => true,
        'name' => true,
        'role' => true,
        'active' => true,
        'first_login' => true,
        'confirmed_email' => true,
        'created' => true,
        'modified' => true,
        'english_level' => true,
        'purchasing_exp' => true,
        'acting_cat' => true,
        'bank_username' => true,
        'bank_cpf' => true,
        'bank_agency' => true,
        'bank_account' => true,
        'bank_name' => true,
        'birth_date' => true,
        'doc_cpf' => true,
        'doc_rg' => true,
        'telephone' => true,
        'cellphone' => true,
        'address_zipcode' => true,
        'address_street' => true,
        'address_number' => true,
        'address_complement' => true,
        'address_city' => true,
        'address_uf' => true,
        'address_neighborhood' => true,
        'corporate' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
    
    public function hash($password)
    {
        return (new DefaultPasswordHasher())->hash($password);
    }

    public function checkPassword($password, $hashedPassword)
    {
        return (new DefaultPasswordHasher())->check($password, $hashedPassword);
    }

    public function generateRandomPassword($length)
    {
        if (intval($length) == 0) {
            return '';
        }

        $l = 'abcdefghijklnmopqrstuvwxyz';
        $u = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $n = '0123456789';

        $all = "{$l}{$u}{$n}";

        $str = substr($l, mt_rand(0, strlen($l)-1), 1);
        for ($i = 0; $i < $length - 1; $i++) {
            $str .= substr($all, mt_rand(0, strlen($all)-1), 1);
        }

        return $str;
    }

    public function getPendentStatus()
    {
        return 'Pendente';
    }
}
