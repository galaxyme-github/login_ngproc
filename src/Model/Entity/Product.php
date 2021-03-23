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
class Product extends Entity
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
        'item_name' => true,
        'model' => true,
        'category_item_prod' => true,
        'manufacturer' => true,
        'cotation_product_items' => true,
        'sku' => true
    ];

    public function getCategoryName() {
        switch ($this->category_item_prod) {
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
