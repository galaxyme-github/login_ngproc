<?php
namespace App\Controller\Admin;

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
        $this->loadComponent('Paginator');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index(){

    }
    public function financeiro(){
        $usersTable = TableRegistry::get('Users');
        $purchaseTable = TableRegistry::get('Purchases');
        $cotationsTable = TableRegistry::get('Cotations');

        $purchasesPagas = $purchaseTable
            ->find()
            ->where(['status !=' => 0, 'commission_pay is null'])
            ->order(['payment_date' => 'DESC'])
            ->all()
            ->toArray();

        $cotationParaComissao = [];
        foreach ($purchasesPagas as $k => $pp) {
            if($pp->status == 3 || $pp->status == 4 || $pp->status == 8){
                $cotation = $cotationsTable->get($pp->cotation_id);
                $cotation = $cotationsTable->find()->contain(['Users','Purchases', 'CotationServices', 'CotationProducts' => 'CotationProductItems.Products', 'CotationProviders' => 'Providers'])
                ->leftJoinWith('CotationServices')
                ->leftJoinWith('Users')
                ->leftJoinWith('CotationProducts.CotationProductItems.Products')
                ->select()
                ->distinct(['Cotations.id'])
                ->where(["Cotations.id" => $pp->cotation_id])
                ->first();
                $cotationParaComissao[$k] = array(
                    'purchase' => $pp,
                    'cotation' => $cotation
                );
            }
        }
        $this->set(compact('cotationParaComissao'));
    }

    public function cotation(){
        $purchaseTable = TableRegistry::get('Purchases');
        $cotationsTable = TableRegistry::get('Cotations');

        $purchases = $purchaseTable
            ->find()
            ->where(['status IS NOT NULL'])
            ->order(['payment_date' => 'DESC']);

        $query = $this->request->getQueryParams();
        if (!empty($query['purchases-search'])) {
            $purchases->andWhere(['name LIKE' => "%{$query['purchases-search']}%"]);
        }

        $this->paginate = ['maxLimit' => 15];
        $purchases = $this->paginate($purchases);
        $this->set(compact('purchases'));

        $purchasesCotations = [];
        foreach ($purchases as $k => $pp) {

            $cotation = $cotationsTable->get($pp->cotation_id);

            //Buscando cotation enviada pelo parceiro (Comprada pelo cliente)
            $cotation = $cotationsTable->find()
            ->contain([
                'Users',
                'Purchases',
                'CotationAttachments',
                'CotationServices',
                'CotationProducts' => 'CotationProductItems.Products',
                'CotationProviders' => 'Providers'
            ])
            ->leftJoinWith('CotationServices')
            ->leftJoinWith('Users')
            ->leftJoinWith('CotationProducts.CotationProductItems.Products')
            ->select()
            ->distinct(['Cotations.id'])
            ->where(["Cotations.id" => $pp->cotation_id])
            ->first();

            //Buscando cotation do cliente
            $main_cotation = $cotationsTable->find()
            ->contain([
                'Users',
                'Purchases',
                'CotationAttachments',
                'CotationServices',
                'CotationProducts' => 'CotationProductItems.Products',
                'CotationProviders' => 'Providers'
            ])
            ->leftJoinWith('CotationServices')
            ->leftJoinWith('Users')
            ->leftJoinWith('CotationProducts.CotationProductItems.Products')
            ->select()
            ->distinct(['Cotations.id'])
            ->where(["Cotations.id" => $cotation->main_cotation_id])
            ->first();

            $purchasesCotations[$k] = array(
                'purchase' => $pp,
                'cotation' => $cotation,
                'main_cotation' => $main_cotation
            );
        }
        $this->set(compact('purchasesCotations'));



        // $this->set(compact('cotations'));
    }

    public function detail($id = null){
        $cotationsTable = TableRegistry::get('Cotations');
        $purchasesTable = TableRegistry::get('Purchases');

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

        $purchase = $purchasesTable->find()->where(['id' => $cotation->purchase->id])->first();
        
        $cli_cotations = $cotationsTable->find()->contain(['Users','Purchases', 'CotationServices', 'CotationAttachments', 'CotationProducts' => 'CotationProductItems.Products', 'CotationProviders' => 'Providers'])
        ->leftJoinWith('CotationServices')
        ->leftJoinWith('CotationAttachments')
        ->leftJoinWith('Users')
        ->leftJoinWith('CotationProducts.CotationProductItems.Products')
        ->select()
        ->distinct(['Cotations.id'])
        ->where(["Cotations.id" => $cotation->main_cotation_id]);
        foreach ($cli_cotations as $key => $value) {
            $cli_cotation = $value;
        }
        
        $this->set(compact('cotation','purchase','cli_cotation'));
    }

    public function efetuarPagamentoComissao(){
        $response = [
            'result' => 'failed'
        ];
        if ($this->request->is(['patch', 'post', 'put'])) {
            $requestData = $this->request->getData();

            $purchaseTable = TableRegistry::get('Purchases');
            $cotationsTable = TableRegistry::get('Cotations');

            $result = $purchaseTable->get($requestData['id']);
            $result->commission_pay = 1;

            $cotation = $cotationsTable->get($result['cotation_id']);

            if($purchaseTable->save($result)){
                // $this->sendPayComission($result['id'], $cotation['user_id'], $response['effectiveValue']);
                $response['result'] = 'success';
                $response['data'] = array(
                    "commissionId" => $result->id,
                    "userId" => $cotation->user_id
                );
            }
        }
        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }
    public function sendPayComission($commissionId, $userId, $commissionValue) {
        // Cria o cURL
        $curl = curl_init();
        // Seta algumas opções
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://ngproc.com.br/sendgrid/index.php',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => [
                commissionValue => $commissionValue,
                commissionId => $commissionId,
                userId =>  $userId,
                type =>  3, //WarningCommission
            ]
        ]);
        // Envia a requisição e salva a resposta
        $response = curl_exec($curl);
        // Fecha a requisição e limpa a memória
        curl_close($curl);
        return;
    }
}
