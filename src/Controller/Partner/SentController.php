<?php
namespace App\Controller\Partner;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;
/**
 * Quotation Controller
 *
 *
 * @method \App\Model\Entity\Quotation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SentController extends AppController{
    public function initialize(){

        parent::initialize();
        $this->viewBuilder()->setLayout('dashboard');

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index(){


        $usersTable = TableRegistry::get('Users');
        $user = $this->request->getAttribute('identity');
        // $cotations = $cotationsTable->find()->contain(['CotationServices', 'CotationProducts' => 'CotationProductItems']);

        $firstLogin = false;
        if (!$user->first_login) {
            $u = $usersTable->get($user->id);
            // $firstLogin = $user->first_login;
            if (!$u->first_login) {
                $firstLogin = true;
                $u->first_login = 1;
                $usersTable->save($u);
            }
        }

        $this->set(compact('firstLogin'));

        $cotationsTable = TableRegistry::get('Cotations');

        $cotations = $cotationsTable->find()->contain([
            'CotationServices',
            'CotationProducts' => 'CotationProductItems',
            'CotationProviders',
            'Purchases'
            ])
            ->select()
            ->distinct(['Cotations.id'])
            ->where([
                'Cotations.user_id' => $user->id,
                'Cotations.main_cotation_id IS NOT NULL'
            ])
            ->order(['Cotations.id' => 'DESC']);

            // Paginator
            $query = $this->request->getQueryParams();

            if (!empty($query['cotacao-search'])) {
                $cotations->andWhere(['title LIKE' => "%{$query['cotacao-search']}%"]);
            }
            $this->paginate = ['maxLimit' => 15];
            $cotations = $this->paginate($cotations);

            // Saber expectativa de orçamento do cliente
            $clienteOrcamento = [];
            foreach ($cotations as $k => $y) {
                if ($y->type == 0) {
                    // RESGATANDO ESPECTATIVA DO CLIENTE
                    $n = $cotation = $cotationsTable->find()->contain([
                        'CotationProducts' => 'CotationProductItems',
                        ])->where([
                            'Cotations.id' => $y->main_cotation_id
                        ])->first();

                    $clienteOrcamento[$k]['idEnviada'] = $y->id;
                    $clienteOrcamento[$k]['expecCliente'] = $n['cotation_product']['estimate'];
                }else if($y->type == 1){
                    // RESGATANDO ESPECTATIVA DO CLIENTE
                    $n = $cotation = $cotationsTable->find()->contain([
                        'CotationServices'
                        ])->where([
                            'Cotations.id' => $y->main_cotation_id
                        ])->first();

                    $clienteOrcamento[$k]['idEnviada'] = $y->id;
                    $clienteOrcamento[$k]['expecCliente'] = $n['cotation_service']['estimate'];
                }
            }



        $this->set(compact('cotations','clienteOrcamento'));
    }

    /**
     * View method
     *
     * @param string|null $id Quotation id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $cotationsTable = TableRegistry::get('Cotations');
        $cotationProvidersTable = TableRegistry::get('CotationProviders');
        $cotationServicesTable = TableRegistry::get('CotationServices');

        $cotations = $cotationsTable->find()->contain(['Users','Purchases', 'CotationAttachments', 'CotationServices', 'CotationProducts' => 'CotationProductItems.Products' ])
        ->leftJoinWith('CotationServices')
        ->leftJoinWith('CotationAttachments')
        ->leftJoinWith('Users')
        ->leftJoinWith('CotationProducts.CotationProductItems.Products')
        ->where(["Cotations.id" => $id]);
        $cotation = $cotations->toArray();
        $cotation = $cotation[0];

        // RECUPERAR EXPECTATIVA DO CLIENTE
        $cotation_cliente = $cotationsTable->get($cotation['main_cotation_id'], [
            'contain' => ['CotationServices', 'CotationProducts' => 'CotationProductItems']
        ]);

        $expecCliente = $cotation_cliente->cotation_product->estimate;

        $c = $cotationProvidersTable->find()->contain(['Providers'])
        ->leftJoinWith('Providers')
        ->where(['cotation_id' => $cotation['id']])->all()->toArray();
        foreach ($c as $k => $y) {
            $providers[$k] = $y['provider'];
        }

        if($cotation->type == 0){

        }else if($cotation->type == 1){
            $cs = $cotationServicesTable->find()
            ->where(['cotation_id' => $cotation['id']])->all()->toArray();
            $cotation['cotation_service'] = $cs;
        }

        $cotation['cotation_providers'] = $c;

        $this->set(compact('cotation','providers','expecCliente'));


















        // $user = $this->request->getAttribute('identity');

        // $cotationsTable = TableRegistry::get('Cotations');

        // //$c =  $cotationsTable->find()->where(['main_cotation_id' => $id])->first()->toArray();

        // $cotation = $cotationsTable->get($id, [
        //     'contain' => [
        //         'CotationServices',
        //         'CotationProducts.CotationProductItems.Products',
        //         'CotationAttachments',
        //     ]
        // ]);

        // $cotation = $cotationsTable->find()->where(['main_cotation_id' => $id, 'user_id' => $user->id])->first();
        // if (!is_null($cotation)) {
        //     $cotation=  $cotation->toArray();
        // }else{
        //     return;
        // }
        // //Se for cotação de produto
        // if($cotation['type'] == 0){
        //     $cotationProductsTable = TableRegistry::get('CotationProducts');
        //     $cotationProductItemsTable = TableRegistry::get('CotationProductItems');
        //     $productsTable = TableRegistry::get('Products');
        //     $cotationProvidersTable = TableRegistry::get('CotationProviders');
        //     $providersTable = TableRegistry::get('Providers');

        //     $cotation["cotation_product"] = $cotationProductsTable->find()->where(["cotation_id" => $cotation['id']])->all()->toArray();

        //     foreach($cotation['cotation_product'] as $cotProduct){
        //         $cotProduct['cotation_product_items'] = $cotationProductItemsTable->find()->where(["cotation_product_id" => $cotProduct['id']])->all()->toArray();

        //         foreach($cotProduct['cotation_product_items'] as $item){
        //             $item['product'] = $productsTable->find()->where(["id" => $item['product_id']])->first()->toArray();
        //         }
        //     }

        //     $cotation["cotation_provider"] = $cotationProvidersTable->find()->where(["cotation_id" => $cotation['id']])->all()->toArray();

        //     foreach($cotation["cotation_provider"] as $item){
        //         $item['provider'] = $providersTable->find()->where(["id" => $item['provider_id']])->first()->toArray();
        //     }
        // }

        // //Se for cotação de serviço
        // if($cotation['type'] == 1){
        //     $cotationServicesTable = TableRegistry::get('CotationServices');
        //     $cotation['cotation_service'] = $cotationServicesTable->find()->where(['cotation_id' => $cotation['id']])->first()->toArray();
        // }

        // $cotationAttachmentTable = TableRegistry::get('CotationAttachments');
        // $cotation['cotation_attachments'] = $cotationAttachmentTable->find()->where(['cotation_id' => $id])->all()->toArray();

        // $this->set(compact('cotation'));
    }
}
