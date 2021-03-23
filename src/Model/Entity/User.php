<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Routing\Router;

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
 * @property string|null $english_level
 * @property string|null $purchasing_exp
 * @property string|null $acting_cat
 * @property string|null $bank_username
 * @property string|null $bank_cpf
 * @property string|null $bank_agency
 * @property string|null $bank_account
 * @property string|null $bank_name
 * @property int|null $corporate
 * @property \Cake\I18n\FrozenDate|null $birth_date
 * @property string|null $doc_cpf
 * @property string|null $doc_rg
 * @property string|null $telephone
 * @property string|null $cellphone
 * @property string|null $address_zipcode
 * @property string|null $address_street
 * @property string|null $address_number
 * @property string|null $address_complement
 * @property string|null $address_city
 * @property string|null $address_uf
 * @property string|null $address_neighborhood
 * @property string|null $token
 * @property bool|null $confirmed_cellphone
 *
 * @property \App\Model\Entity\Cotation[] $cotations
 * @property \App\Model\Entity\Person[] $persons
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
        'corporate' => true,
        'birth_date' => true,
        'doc_cpf' => true,
        'doc_cnpj' => true,
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
        'token' => true,
        'confirmed_cellphone' => true,
        'cotations' => true,
        'persons' => true,
        'img_profile' => true,
        'optout_email' => true,
        'sent_email' => true,
        'terms_of_use' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'token'
    ];

    public function nameFormatted(){
        $nameArray = preg_split('" "', strtoupper($this->name)); 

                                        
        $name = $nameArray[0];
        $parceiroName = $name;
        if(count($nameArray) > 1){
            $lastName = str_split($nameArray[1]);
            $parceiroName = $parceiroName . " " . $lastName[0] . ".";
        }
        return $parceiroName;
    }
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

    public function getProfileUrl() {
        //Definindo o nome da imagem padrão que
        //irá aparecer caso o usuário não defina nenhuma.
        $ImageDefaultProfile = "profile-default.png";

        //Testando se não há nenhuma imagem salva no banco.
        if (empty($this->img_profile)) {
            return Router::url("uploads/profile/{$ImageDefaultProfile}");
        }else {
            return Router::url("uploads/profile/{$this->img_profile}");
        }
    }

    public function getNameImageProfile() {
        return $this->img_profile;
    }
}