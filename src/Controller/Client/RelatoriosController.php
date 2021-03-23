<?php
namespace App\Controller\Client;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;
/**
 * Quotation Controller
 *
 *
 * @method \App\Model\Entity\Quotation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RelatoriosController extends AppController{
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
        $user = $this->request->getAttribute('identity');

        $usersTable = TableRegistry::get('Users');
        $purchaseTable = TableRegistry::get('Purchases');
        $cotationsTable = TableRegistry::get('Cotations');

        $purchasesPagas = $purchaseTable->find()->where(['user_id' => $user->id])->order(['cotation_id' => 'DESC'])->all()->toArray();

        $cotationPagasPorMim = [];
        foreach ($purchasesPagas as $k => $pp) {
            //Trazendo as cotações pagas pelo cliente
            $cotation = $cotationsTable->get($pp->cotation_id, ['contain' =>
                [
                'CotationServices',
                'CotationProducts' => 'CotationProductItems',
                'CotationProviders'
                ]
            ]);
            $cotationPagasPorMim[$k] = array(
                'purchase' => $pp,
                'cotation' => $cotation
            );
        }

        // $cotations = $cotationsTable->find()->contain(['Users','Purchases', 'CotationServices', 'CotationProducts' => 'CotationProductItems.Products', 'CotationProviders' => 'Providers'])
        // ->leftJoinWith('CotationServices')
        // ->leftJoinWith('Users')
        // ->leftJoinWith('CotationProducts.CotationProductItems.Products')
        // ->select()
        // ->distinct(['Cotations.id'])
        // ->where(["Cotations.id" => $cotationPagas->id]);
        $this->set(compact('cotationPagasPorMim'));
    }

    public function details($id = null){
        $cotationsTable = TableRegistry::get('Cotations');
        $purchasesTable = TableRegistry::get('Purchases');
        $usersTable = TableRegistry::get('Users');

        $cotations = $cotationsTable->find()->contain(['Users','Purchases', 'CotationServices', 'CotationAttachments', 'CotationProducts' => 'CotationProductItems.Products', 'CotationProviders' => 'Providers'])
        ->leftJoinWith('CotationServices')
        ->leftJoinWith('CotationAttachments')
        ->leftJoinWith('Users')
        ->leftJoinWith('CotationProducts.CotationProductItems.Products')
        ->select()
        ->distinct(['Cotations.id'])
        ->where(["Cotations.id" => $id]);
        foreach ($cotations as $key => $value) {
            $cotation = $value;
        }
        // $cotation = $cotationsTable->get($id, [
        //     'contain' => [
        //         'Purchases',
        //         'Users',
        //         'CotationServices',
        //         'CotationProducts' => 'CotationProductItems',
        //         'CotationProviders' => 'Providers']
        // ]);
        $purchase = $purchasesTable->find()->where(['id' => $cotation->purchase->id])->first();
        $client = $usersTable->find()->where(['id' => $purchase->user_id])->first();
        // if($purchase->status == 0){
        //     $purchase->status = 1;
        //     $purchase->date_payment = date('d/m/Y');
        //     $purchasesTable->save($purchase);
        // }

        // $query = $this->request->getQueryParams();

        // if($query){
        //     $payId = $query['paymentId'];
        //     dump($this->makePaymentPaypal($query));
        // }
        $this->set(compact('cotation','purchase', 'client'));
    }

    //============================================
    //     PRÉ VISUALIZAÇÃO ANTES DO ACEITE
    //============================================
    public function preview($id = null){
        $cotationsTable = TableRegistry::get('Cotations');
        $cotationProvidersTable = TableRegistry::get('CotationProviders');
        $cotationServicesTable = TableRegistry::get('CotationServices');

        $cotations = $cotationsTable->find()->contain(['Users','Purchases', 'CotationServices', 'CotationProducts' => 'CotationProductItems.Products' ])
        ->leftJoinWith('CotationServices')
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
    }
}
