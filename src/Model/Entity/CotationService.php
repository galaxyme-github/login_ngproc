<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CotationService Entity
 *
 * @property int $id
 * @property string $description
 * @property string $service_time
 * @property string $category
 * @property string $collection_type
 * @property string $expectation_start
 * @property float $estimate
 * @property int $cotation_id
 *
 * @property \App\Model\Entity\Cotation $cotation
 */
class CotationService extends Entity
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
        'description' => true,
        'service_time' => true,
        'category' => true,
        'collection_type' => true,
        'expectation_start' => true,
        'estimate' => true,
        'cotation_id' => true,
        'provider_id' => true,
        'cotation' => true
    ];
    public function getCategoryName()
    {
        switch ($this->category) {
            case 0:
                return "Todas as categorias";
                
            case 1:
                return "Apps e Jogos";

            case 2:
                return "Bebês";

            case 3:
                return "Beleza";

            case 4:
                return "Bolsas, Malas e Mochila";

            case 5:
                return "Brinquedos e Jogos";

            case 6:
                return "Casa";

            case 7:
                return "CD e Vinil";

            case 8:
                return "Computadores e Informática";

            case 9:
                return "Cozinha";

            case 10:
                return "DVD e Blu-Ray";

            case 11:
                return "Eletrodomésticos";

            case 12:
                return "Eletrônicos";

            case 13:
                return "Esporte e Aventura";

            case 14:
                return "Ferramentas e Materiais de Construção";

            case 15:
                return "Games";

            case 16:
                return "Jardim e Piscina";

            case 17:
                return "Livros";

            case 18:
                return "Material de Escritório e Papelaria";

            case 19:
                return "Móveis e Decoração";

            case 20:
                return "Roupas, Calçados e Joias";

            case 21:
                return "Saúde e Cuidados Pessoais";

            case 22:
                return "Lista Compras Supermercado";

            case 23:
                return "Médico Hospitalar";

            case 24:
                return "Turismo";

            case 25:
                return "Químico";

            case 26:
                return "MRO";

            case 27:
                return "Clube de Assinaturas";

            case 28:
                return "Cursos Online";

            case 29:
                return "Nenhuma das Listadas";

            case 30:
                return "Outros";
        };
    }
}
