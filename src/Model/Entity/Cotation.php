<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cotation Entity
 *
 * @property int $id
 * @property string $title
 * @property string $cotation_type
 * @property int $provider_qtd
 * @property string $objective
 * @property string $deadline_date
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property string $coverage
 *
 * @property \App\Model\Entity\CotationProduct[] $cotation_products
 * @property \App\Model\Entity\CotationService[] $cotation_services
 */
class Cotation extends Entity
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
        'type' => true,
        'provider_qtd' => true,
        'objective' => true,
        'deadline_date' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'coverage' => true,
        'cotation_product' => true,
        'cotation_service' => true,
        'cotation_attachments' => true,
        'cotation_providers' => true,
        'puchase' => true,
        'main_cotation_id' => true,
        'address_zipcode' => true,
    ];

    public function getBudgetExpectation()
    {
        if ($this->type == 0 && $this->cotation_product) {
            // pra isso teria que fazer uma parada aqui que desse loop nos itens e iria somando
            // $soma = 0;
            return $this->cotation_product->estimate;
        } elseif ($this->type == 1 && $this->cotation_service) {
            if (is_array($this->cotation_service)) {
                return $this->cotation_service[0]->estimate;
            }
            return $this->cotation_service->estimate;
        }

        return '';
    }

    public function getItensCotations()
    {
        if ($this->type == 0 && $this->cotation_product) {
            $cotation_product = $this->cotation_product;
            $products = [];

            if($cotation_product->cotation_product_items){
                foreach($cotation_product->cotation_product_items as $product_item){
                    if($product_item->product){
                        array_push($products, $product_item->product->item_name);
                    }
                }
            }

            return implode(",", $products);
        }

        return '';
    }

    public function getPercentViewCotation(){
        $value_expectation = $this->getBudgetExpectation();
        $percent = $value_expectation * 0.02;

        if($percent > 7)
            return $percent;

        return 7;
    }

    public function getPercentViewCotationParams($value){
        $percent = $value * 0.02;

        if($percent > 7)
            return $percent;

        return 7;
    }

    public function getVerifyPayedCotation(){


        if($this->purchase && $this->purchase->status != 0)
            return true;

        return false;
    }

    public function lblStatus(){
        switch ($this->status) {
            case '0':
                return "Aguardando parceiros";
                break;
            case '1':
                return "Em andamento";
        }
    }
}
