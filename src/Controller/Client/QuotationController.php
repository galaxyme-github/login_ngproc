<?php
namespace App\Controller\Client;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\Http\Cookie\CookieCollection;

/**
 * Quotation Controller */

/* @method \App\Model\Entity\Quotation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])*/

class QuotationController extends AppController{
    var $components = array('Cookie');
    public function initialize()
    {

        parent::initialize();
        $this->viewBuilder()->setLayout('dashboard');
    }

    public function formatarData($data){
        $aux = "";
        // $data = str_split($data);
        // for ($i=0; $i < count($data); $i++) {
        //     if($data[$i] == "-"){
        //         $data[$i] = "/";
        //         $aux = $aux . $data[$i];
        //     }else{
        //         $aux = $aux . $data[$i];
        //     }
        // }

        $data = preg_split('"/"',$data);
        $y = count($data) -1;
        for ($i = $y; $i >= 0; $i--) {
            if($i == 0){
                $aux = $aux . $data[$i];
            }else{
                $aux = $aux . $data[$i] . '/';
            }
        }

        return $aux;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */

    public function index(){
        $user = $this->request->getAttribute('identity');
        $usersTable = TableRegistry::get('Users');
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

        // $imgProfileUser = $this->getImageProfile($user);
        // $this->set(compact('imgProfileUser'));
        $this->set(compact('firstLogin'));

        $cotationsTable = TableRegistry::get('Cotations');
        // $user = $this->request->getAttribute('identity');

        $cotations = $cotationsTable->find()->contain(['CotationServices', 'CotationProducts' => 'CotationProductItems'])->where(['user_id' => $user->id, 'main_cotation_id IS NULL']);
        //VALIDANDO SE COTAÇÃO EXPIRADA
        foreach($cotations as $k => $c){
            // if(strtotime($c->created->format('d/m/Y')) > strtotime(date('d-m-Y'))){
            if(strtoupper($c->deadline_date) != "TEMPO INDETERMINADO"){
                if( strtotime( $this->formatarData($c->deadline_date) ) < strtotime( date('Y/m/d') ) ){
                    $cot = $cotationsTable->get($c->id);
                    $cot->status = 2;
                    $cotationsTable->save($cot);
                }
            }
        }

        $cotations = $cotationsTable->find()->order(['Cotations.id' => 'DESC'])->contain(['CotationServices', 'CotationProducts' => 'CotationProductItems'])->where(['user_id' => $user->id, 'main_cotation_id IS NULL']);
        $query = $this->request->getQueryParams();
        if (!empty($query['cotacao-search'])) {
            $cotations->andWhere(['title LIKE' => "%{$query['cotacao-search']}%"]);
        }

        $this->paginate = ['limit' => 300];
        $cotations = $this->paginate($cotations);
        // dump($cotations);
        $this->set(compact('cotations'));
    }

    /**
     * View method
     *
     * @param string|null $id Quotation id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    //============================================
    //                     VIEW
    //============================================
    // public function view($id = null){
    //     $cotationsTable = TableRegistry::get('Cotations');
    //     $cotationServicesTable = TableRegistry::get('CotationServices');

    //     $main_cotation = $cotationsTable->get($id, [
    //         'contain' => ['CotationServices', 'CotationProducts' => 'CotationProductItems']
    //     ]);

    //     $cotations = $cotationsTable->find()->contain([
    //         'Users',
    //         'Purchases',
    //         'CotationServices',
    //         'CotationProducts' => 'CotationProductItems.Products',
    //         'CotationProviders',
    //     ])->leftJoinWith('CotationServices')
    //     ->leftJoinWith('Users')
    //     ->leftJoinWith('CotationProducts.CotationProductItems.Products')
    //     ->select()
    //     ->distinct(['Cotations.id'])
    //     ->where(["Cotations.main_cotation_id" => $id, "Cotations.status <>" => 4]);
    //     $arrayService = [];
    //     if($main_cotation->type == 0){
    //         $expecCliente = $main_cotation->cotation_product->estimate;
    //     }else{
    //         $expecCliente = $main_cotation->cotation_service->estimate;
    //         foreach ($cotations as $key => $value) {
    //             # code...
    //             $arrayService[$key] = $cotationServicesTable->find()->where(["cotation_id" => $value['id']])->all()->toArray();
    //         }
    //     }


    //     $query = $this->request->getQueryParams();
    //     if (!empty($query['cotacao-search'])) {
    //         $cotations->andWhere(['title LIKE' => "%{$query['cotacao-search']}%"]);
    //     }

    //     $this->paginate = ['maxLimit' => 25];

    //     $cotations = $this->paginate($cotations);

    //     //debug($cotations);
    //     //exit;
    //     $this->set(compact('cotations'));
    //     $this->set(compact('main_cotation'));
    //     $this->set(compact('expecCliente'));
    //     $this->set(compact('arrayService'));
    // }
    public function view($id = null){
        $cotationsTable = TableRegistry::get('Cotations');

        $main_cotation = $cotationsTable->find()
            ->contain([
                'Users',
                'Purchases',
                'CotationServices',
                'CotationProducts' => 'CotationProductItems.Products',
                'CotationProviders',
            ])
            ->leftJoinWith('CotationServices')
            ->leftJoinWith('Users')
            ->leftJoinWith('CotationProducts.CotationProductItems.Products')
            ->select()
            ->distinct(['Cotations.id'])
            ->where(['Cotations.id' => $id])
            ->first();

        // $sents_partners = array(
        //     'main_cotation_id' => $main_cotation['id'],
        //     'cotation_product_items' => $main_cotation['cotation_product']['cotation_product_items'],
        //     'cotations' => []
        // );

        $result = $cotationsTable->find()->contain([
            'Users',
            'Purchases',
            'CotationServices',
            'CotationProducts' => 'CotationProductItems.Products',
            'CotationProviders.Providers',
        ])->leftJoinWith('CotationServices')
        ->leftJoinWith('Users')
        ->leftJoinWith('CotationProducts.CotationProductItems.Products')
        ->select()
        ->distinct(['Cotations.id'])
        ->where([
            "Cotations.main_cotation_id" => $id,
            "Cotations.status <>" => 4
        ])
        ->toArray();

        $cotations = [];
        foreach ($result as $key => $r) {
            if(empty($r->purchase)){
                array_push($cotations, $r);
            }
        }

        // dump($cotations);
        // exit;

        //$control = (($page_partner - 1) * $limit);
        // foreach ($cotations as $key => $c) {
        //     // foreach ($c['cotation_product']['cotation_product_items'] as $k => $c_item) {
        //     //     if($c_item['id'] == $main_cotation['cotation_product']['cotation_product_items'][$item]['id']){
        //     //         if($key >= $control && $key < ($control + $limit) ){
        //                 array_push($sents_partners['cotations'], $cotations[$key]['cotation_product']['cotation_product_items']);
        //     //         }
        //     //     }
        //     // }
        // }

        // dump($main_cotation);
        // exit;
        $this->set(compact('cotations'));
        $this->set(compact('main_cotation'));
        //$this->set(compact('sents_partners'));
    }


    //============================================
    //     PRÉ VISUALIZAÇÃO ANTES DO ACEITE
    //============================================
    public function viewPreview($id = null){
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
            $expecCliente = $cotation_cliente->cotation_service->estimate;

        }

        $cotation['cotation_providers'] = $c;

        // dump($cotation);
        // exit;

        $this->set(compact('cotation','providers','expecCliente'));
    }

    //GERADOR DE CÓDIGOS
    function random_str_mt($size = 8){
        $keys = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

        $key = '';
        for ($i = 0; $i < ($size+10); $i++)
        {
            $key .= $keys[array_rand($keys)];
        }

        return substr($key, 0, $size);
    }

    //=====================================================
    //          PAGAMENTO COM PAGSEGURO
    //=====================================================
    public function createPaymentPagSeguro(){
        $response = [
            'result' => 'failed',
        ];
      
        $myfile = fopen("/var/http/www/dev/webroot/js/lala.js", "wa") or die("Unable to open file!");
                
        if ($this->request->is('post')) {
            $requestData = $this->request->getData();
            $cotationsTable = TableRegistry::get('Cotations');
            $purchaseTable = TableRegistry::get('Purchases');

            $cotation = $cotationsTable->get($requestData['id'], [
                'contain' => ['CotationServices', 'CotationProducts' => 'CotationProductItems']
            ]);

            $user = $this->request->getAttribute('identity');
            // $codeReference = $this->random_str_mt(10);

            $exit = $purchaseTable
                ->find()
                ->where(['user_id' => $user->id])
                ->first();


            $purchase = $purchaseTable->newEntity();
            $purchase->value = $requestData['valor'];
            $purchase->user_id = $user->id;
            $purchase->cotation_id = $cotation->id;
            $purchase->status = (int) 0;
            $purchase->discounted = empty($exit) ? 1 : null;
            $purchase->payment_date = date('d/m/Y');
            if($purchaseTable->save($purchase)){
                // Atualizando status da cotação do parceiro para "aceita" = 5
                $result = $cotationsTable->get($cotation->id);

                $result->status = 5;
                $cotationsTable->save($result);
                try {
                $this->registerEvaluation($cotation);
                } catch (\Throwable $e) {
                    fwrite($myfile, 'and the error is: ' .  $e->getMessage());
                }
            }
            
            $data['token'] = Configure::read('App.tokenPagSeguro');
            $data['email'] = Configure::read('App.emailPagSeguro');
            $data['currency'] = 'BRL';
            $data['itemId1'] = '1';
            $data['itemQuantity1'] = '1';
            $data['itemDescription1'] = 'Cotacao: '. $cotation->title . ' - Pedido: ' . $purchase->id;
            $data['itemAmount1'] = $requestData['valor'];
            $data['reference'] = $purchase->id;
            // $data['reference'] = $codeReference;

            $url = Configure::read('App.urlPagSeguro');

            $data = http_build_query($data);

            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded'
            );
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
            $xml= curl_exec($curl);
          
            /*$opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-Type: application/x-www-form-urlencoded',
                    'content' => $data
                )
            );

            $context  = stream_context_create($opts);
            
            $xml = file_get_contents($url, false, $context);*/

            if($xml == 'Unauthorized'){
                $return = 'Não Autorizado';
                fwrite($myfile, 'ola8');
                echo $return;
                exit;
            }

            //curl_close($curl);

            $tempLocalPath = "/var/http/www/dev/webroot/js/lala.xml";
            file_put_contents($tempLocalPath, $xml);
            fwrite($myfile, $url . '?' . $data);
            //fwrite($myfile, $url . $data);
          
            $xml = simplexml_load_string($xml);

            if(count($xml->error) > 0){
                $return = 'Dados Inválidos '.$xml->error->message;
                echo $return;
                exit;
            }
            $response['code'] = $xml->code;
            //fwrite($myfile, $xml->code);
            if(!empty($response['code'])){
                $response['result'] = 'success';
            }
        }
        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
      
        fclose($myfile);
    }

    //=====================================================
    //          VERIFICAR SE DEVE APLICA DESCONTO
    //=====================================================
    public function isPurchasesUser(){
        //Esse é um valor de desconto oferecido pela NGPROC
        //apenas na primeira compra deste cliente
        $DISCOUNT = 10;

        $response = [
            'result' => 'failed',
        ];

        if ($this->request->is('post')) {
            $requestData = $this->request->getData();
            $purchaseTable = TableRegistry::get('Purchases');
            $user = $this->request->getAttribute('identity');
            $exit = $purchaseTable
                ->find()
                ->where(['user_id' => $user->id])
                ->first();
            if( empty($exit) ){
                $response['discount'] = true;
                $discountedValue = (float) $requestData['valor'];
                if($discountedValue <= $DISCOUNT){ $discountedValue = 0;
                }else{ $discountedValue -= $DISCOUNT; }
                $response['discountedValue'] = $discountedValue;
            }else{
                $response['discount'] = false;
            }
            $response['result'] = 'success';
        }
        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }

    public function applyDiscount(){
        $response = [
            'result' => 'failed',
        ];

        if ($this->request->is('post')) {
            $requestData = $this->request->getData();
            $purchaseTable = TableRegistry::get('Purchases');
            $cotationsTable = TableRegistry::get('Cotations');
            $user = $this->request->getAttribute('identity');

            $cotation = $cotationsTable
                ->get($requestData['id'], [
                    'contain' => ['CotationServices', 'CotationProducts' => 'CotationProductItems']
                ]);

            $purchase = $purchaseTable->newEntity();
            $purchase->value = $requestData['valor'];
            $purchase->user_id = $user->id;
            $purchase->cotation_id = $cotation->id;
            $purchase->status = 3;
            $purchase->discounted = 1;
            $purchase->payment_date = date('d/m/Y');

            if($purchaseTable->save($purchase)){
                $this->registerEvaluation($cotation);
                // Atualizando status da cotação do parceiro para "aceita" = 5
                $result = $cotationsTable->get($cotation->id);
                $result->status = 5;
                $cotationsTable->save($result);
                $response['result'] = 'success';
            }
        }
        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }

    public function deletePurchaseForCodeTransaction(){
        $response = [
            'result' => 'failed',
        ];

        if ($this->request->is('post')) {
            $code_transaction = $this->request->getData();
            $purchaseTable = TableRegistry::get('Purchases');
            $result = $purchaseTable->find()->where(['code_transaction' => $code_transaction['code']])->first();
            if( $result ){
                $result = $purchaseTable->get($result->id);
                if($purchaseTable->delete($result)){
                    $response['result'] = 'success';
                }
            }
        }
        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }

    public function registerEvaluation($cotation) {
        $user = $this->request->getAttribute('identity');
        $EvaluationsTable = TableRegistry::get('Evaluations');
        $evaluation = $EvaluationsTable->newEntity();
        $evaluation->client_id = $user->id;
        $evaluation->parter_id = $cotation->user_id;
        $evaluation->cotation_id = $cotation->id;
        $evaluation->owner = 'parter';
        $EvaluationsTable->save($evaluation);
        $evaluation = $EvaluationsTable->newEntity();
        $evaluation->client_id = $user->id;
        $evaluation->parter_id = $cotation->user_id;
        $evaluation->cotation_id = $cotation->id;
        $evaluation->owner = 'client';
        $EvaluationsTable->save($evaluation);

        $this->sendPayCotation($cotation);
    }
    function sendPayCotation($cotation) {
        $user = $this->request->getAttribute('identity');
        // Cria o cURL
        $curl = curl_init();
        // Seta algumas opções
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://sendgrid.ngproc.com.br/index.php',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => [
                'idPartner' => $cotation->user_id,
                'cotationIdPartner' => $cotation->id,
                'idClient' =>  $user->id,
                'type' =>  4, //WarningPurchased
            ]
        ]);
        // Envia a requisição e salva a resposta
        $response = curl_exec($curl);
        // Fecha a requisição e limpa a memória
        curl_close($curl);
    }

    //=====================================================
    //      CRIANDO PAGAMENTO COM PAYPAL
    //=====================================================
    // public function createPaymentPaypal(){

    //     $query = $this->request->getQueryParams();

    //     if($query){

    //         $cotationsTable = TableRegistry::get('Cotations');

    //         $cotation = $cotationsTable->get($query['id'], [
    //             'contain' => ['CotationServices', 'CotationProducts' => 'CotationProductItems']
    //         ]);
    //         // $cotation = $cotationsTable->get($id, [
    //         //     'contain' => ['CotationServices', 'CotationProducts' => 'CotationProductItems']
    //         // ]);
    //         $user = $this->request->getAttribute('identity');

    //         //debug($user);
    //         //exit;

    //         $purchaseTable = TableRegistry::get('Purchases');
    //         $purchase = $purchaseTable->newEntity();
    //         $purchase->value = $query['valor'];
    //         // $purchase->value = $cotation->getPercentViewCotation();
    //         $purchase->user_id = $user->id;
    //         $purchase->cotation_id = $cotation->id;
    //         $purchase->status = false;
    //         $purchaseTable->save($purchase);


    //         $apiContext = new \PayPal\Rest\ApiContext(
    //             new \PayPal\Auth\OAuthTokenCredential(
    //                 Configure::read('App.clientIdPayPal'),     // ClientID
    //                 Configure::read('App.clientSecretPayPal')     // ClientSecret
    //             )
    //         );

    //         $apiContext->setConfig([
    //             'mode' => 'live',
    //         ]);

    //         $payer = new \PayPal\Api\Payer();
    //         $payer->setPaymentMethod('paypal');

    //         $amount = new \PayPal\Api\Amount();
    //         $amount->setTotal($query['valor']);
    //         // $amount->setTotal($cotation->getPercentViewCotation());
    //         $amount->setCurrency('BRL');

    //         $transaction = new \PayPal\Api\Transaction();
    //         $transaction->setAmount($amount);
    //         $redirectUrls = new \PayPal\Api\RedirectUrls();
    //         $redirectUrls->setReturnUrl(Configure::read('App.domain'). Router::url(['action' => 'viewDetails', $cotation->id]))
    //                     // ->setCancelUrl(Configure::read('App.domain'). Router::url(['action' => 'view', $cotation->id]));
    //                     ->setCancelUrl(Configure::read('App.domain'). Router::url(['action' => 'view', $query['mainId']]));

    //         $payment = new \PayPal\Api\Payment();
    //         $payment->setIntent('sale')
    //         ->setPayer($payer)
    //         ->setTransactions(array($transaction))
    //         ->setRedirectUrls($redirectUrls);

    //         try {
    //             // $this->setApiContext($apiContext);
    //             $this->setLinkPaypal($payment->getApprovalLink());
    //             $payment->create($apiContext);
    //             $this->redirect($payment->getApprovalLink());
    //         }
    //         catch (\PayPal\Exception\PayPalConnectionException $ex) {
    //             // This will print the detailed information on the exception.
    //             //REALLY HELPFUL FOR DEBUGGING
    //             dump($ex->getData());
    //         }
    //     }
    // }

    public function viewDetails($id = null){

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
        // $cotation = $cotationsTable->get($id, [
        //     'contain' => [
        //         'Purchases',
        //         'Users',
        //         'CotationServices',
        //         'CotationProducts' => 'CotationProductItems',
        //         'CotationProviders' => 'Providers']
        // ]);
        $purchase = $purchasesTable->find()->where(['id' => $cotation->purchase->id])->first();
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
        $this->set(compact('cotation','purchase'));
    }

    public function rejectCotation($id = null){
        $cotationsTable = TableRegistry::get('Cotations');
        $cotation = $cotationsTable->get($id);
        $cotation->status = 4;
        $cotationsTable->save($cotation);

        $cotations = $cotationsTable->find()->contain(['Users','Purchases', 'CotationServices', 'CotationProducts' => 'CotationProductItems.Products' ])
        ->leftJoinWith('CotationServices')
        ->leftJoinWith('Users')
        ->leftJoinWith('CotationProducts.CotationProductItems.Products')
        ->where(["Cotations.main_cotation_id" => $cotation->main_cotation_id, "Cotations.status <>" => 4]);
        $c = $cotations->toArray();
        if(empty($c)){
            $cotation = $cotationsTable->get($cotation->main_cotation_id);
            $cotation->status = 0;
            $cotationsTable->save($cotation);
        }

        $this->redirect(['action' => 'index', $cotation->main_cotation_id]);

    }

    public function cancelCotation($id = null){
        $cotationsTable = TableRegistry::get('Cotations');
        $cotation = $cotationsTable->get($id);
        $cotation->status = 3;
        $cotationsTable->save($cotation);

        $cotations = $cotationsTable->find()
        ->where(["Cotations.main_cotation_id" => $id])
        ->toArray();

        foreach ($cotations as $key => $c) {
            $result = $cotationsTable->get($c['id']);
            $result->status = 3;
            $cotationsTable->save($result);
        }

        $this->redirect(['action' => 'index']);
    }

    public function activeCotation(){
        $response = [
            'result' => 'failed'
        ];

        if ($this->request->is('post')) {
            $cotationsTable = TableRegistry::get('Cotations');
            $requestData = $this->request->getData();
            $id =  (int) $requestData['cotation_id'];
            $cotation = $cotationsTable->get($id);

            $result = $cotationsTable->find()
            ->where(['main_cotation_id' => $id])->toArray();

            if(empty($result)){
                $cotation->status = 0;
            }else{
                $cotation->status = 1;

                foreach ($result as $key => $c_r) {
                    $c_result = $cotationsTable->get($c_r['id']);
                    $c_result->status = 0;
                    $cotationsTable->save($c_result);
                }
            }

            if($cotationsTable->save($cotation)){
                $response['result'] = 'success';
            }
        }
        // $cotationsTable = TableRegistry::get('Cotations');
        // $cotation = $cotationsTable->get($id);
        // $cotation->status = 0;
        // $cotationsTable->save($cotation);

        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
        //$this->redirect(['action' => 'index']);
    }

    /**
     * ENVIANDO EMAIL PARA PARCEIROS
     */
    public function sendGrid($typeSend, $category) {
        $sendGridTable = TableRegistry::get('SendGrids');
        $sendGrid = $sendGridTable->newEntity();
        //Não enviado, ainda.
        $sendGrid->send = false;
        $sendGrid->category = $category;
        $sendGridTable->save($sendGrid);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    //============================================
    //          ADICIONAR NOVA COTAÇÃO
    //============================================
    public function add(){
        $cotationsTable = TableRegistry::get('Cotations');
        $attstable = TableRegistry::get('CotationAttachments');
        $productsTable = TableRegistry::get('Products');

        $association = ['CotationServices', 'CotationProducts.CotationProductItems.Products', 'CotationAttachments'];

        //Preenchendo o array com todos os nome da tabela products
        $productsAll = $this->queryProductAll();
        $rowsProductsAll = $this->getCountProductAll();
        //$this->set(compact('productsAll','rowsProductsAll'));

        if ($this->request->is(['patch', 'post', 'put'])) {

            $requestData = $this->request->getData();
            if (isset($_FILES['anexo'])) {

                if (count($_FILES['anexo']['tmp_name']) > 0) { //verifica se ao menos 1 arquivo foi enviado
                    $totalarquivos = count($_FILES['anexo']['tmp_name']); // Irá contar o total de arquivos enviados
                    $uploaderro = 0;
                    $atts = [];

                    for ($q = 0; $q < $totalarquivos; $q++) {
                        $nomeOriginal = $_FILES['anexo']['name'][$q];
                        $nomedoarquivo = md5($_FILES['anexo']['name'][$q] . time() . rand(0, 999)) . substr($nomeOriginal, strrpos($nomeOriginal, '.'));
                        if (!move_uploaded_file($_FILES['anexo']['tmp_name'][$q], WWW_ROOT . '/uploads/cotations/' . $nomedoarquivo)) {
                            $uploaderro++;
                            continue;
                        }
                        $atts[] = ['name_original' => $nomeOriginal, 'name' => $nomedoarquivo];
                    }
                    $requestData['cotation_attachments'] = $atts;
                }

            }

            //Jogando os dados (arrays) enviados pela view nos respespectivos arrays
            $products = [];

            if (!empty($requestData['cotation_product']) && !empty($requestData['cotation_product']['cotation_product_items']) && count($requestData['cotation_product']['cotation_product_items']) > 0) {
                foreach ($requestData['cotation_product']['cotation_product_items'] as $key => $productItem) {
                    $requestData['cotation_product']['cotation_product_items'][$key]['quote'] = floatval(str_replace(['.', ','], ['', '.'], $productItem['quote']));
                    $products[$key] = $requestData['cotation_product']['cotation_product_items'][$key]['product'];
                }

                //TRATAR SE O ITEM QUE O USUÁRIO ESTÁ INSERINDO JÁ EXISTE NO BANCO
                //VALIDAR DE TODOS OS CAMPOS DE PRODUTOS SÃO OS MESMOS QUE ESTÃO SENDO INSERIDOS, CASO NÃO, ATUALIZAR.
                //PASSAR TODAS AS INSERÇÕES COMO MAÍSCULO OU MINÚSCULO PARA GARANTIR INTEGRIDADE

                //IMPLEMENTAR SE EXISTIR ATUALIZAR O REGISTRO EXISTENTE COM O NOVO A SER INSERIDO
                //AFIM DE CORRIGIR UM PROBLEMA QUE IMPEDI DE SALVAR A COTAÇÃO AO TENTAR SALVAR
                //UM PRODUTO EXISTENTE COM DADOS DIFERENTES

                //Testando se os itens já existem no banco através do nome
                // foreach ($products as $key => $productItem) {
                //     $productsData = $this->findProduct($productItem['item_name']);

                //     if(!empty($productsData)){
                //         // Adicionando o id do product existente na cotation_product_items
                //         $requestData['cotation_product']['cotation_product_items'][$key]['product_id'] = $productsData['id'];
                //         //unset($requestData['cotation_product']['cotation_product_items'][$key]['product']);

                //         if (array_key_exists('product', $requestData['cotation_product']['cotation_product_items'][$key])) {
                //            unset($requestData['cotation_product']['cotation_product_items'][$key]['product']);
                //         }
                //     }
                // }
            }

            if (!empty($requestData['cotation_service']) && count($requestData['cotation_service']) > 0) {
                $requestData ['cotation_service']['estimate'] = floatval(str_replace(['.', ','], ['', '.'], $requestData ['cotation_service']['estimate']));
            }

            $cotation = $cotationsTable->newEntity(null);
            $cotation = $cotationsTable->patchEntity($cotation, $requestData, ['associated' => $association]);

            $user = $this->request->getAttribute('identity');

            $cotation->status = 0; // status "aguardando parceiros"

            $cotation->user_id = $user->id;


            if ($cotation->type == 0) {
                $somaitens = 0;
                foreach ($cotation->cotation_product->cotation_product_items as $k => $item) {
                    $somaitens = $somaitens + ($item->quote * $item->quantity);
                }
                $cotation->cotation_product->estimate = $somaitens;
                $cotation->cotation_service = null;

            } elseif ($cotation->type == 1) {
                $cotation->cotation_product = null;
            }

            //ELIMINANDO OS ITENS QUE TIVER QTD 0 DA COTAÇÃO
            if (isset($cotation->cotation_product->cotation_product_items)){
                foreach ($cotation->cotation_product->cotation_product_items as $k => $item) {
                    if ($item->quantity == 0) {
                        unset($cotation->cotation_product->cotation_product_items[$k]);
                    }
                }
            }

            // if  ($this->request->is(['post'])){

            //     if ($cotationsTable->save($cotation)) {
            //         $this->Flash->success(__('Cotação salva com sucesso.'));
            //         //Envia email com os dados da cotação para todos os parceiros
            //        // $this->sendCotation($cotation);

            //         return $this->redirect(['action' => 'index']);
            //     }
            //     $this->Flash->error(__('A cotação não pode ser salva. Por favor, tente novamente.'));
            // }

            // date_default_timezone_set('America/Sao_Paulo');
            // $date = date('Y-m-d H:i');
            // $cotation->created = $date;
            if ($cotationsTable->save($cotation)) {
                $this->Flash->success(__('Cotação salva com sucesso.'));

                //PEGAR CATEGORIA PARA O SENDGRID
                $categorySendgrid = 0;
                if ($cotation->type  == 1) {
                    //SERVIÇO
                    $categorySendgrid = $cotation->cotation_service->category;

                }else{
                    //PRODUTO
                    $productsTable = TableRegistry::get('Products');
                    foreach ($cotation->cotation_product->cotation_product_items as $i => $itens){
                        $product = $productsTable->get($itens->product_id);
                        $categorySendgrid = $product->category_item_prod;
                    }
                }

                //ENVIANDO EMAILS
                $this->sendGrid(1, $categorySendgrid);//Enviando email de aviso tipo 1: Avisando parceiros da categoria que há uma nova cotação

                // $usersTable = TableRegistry::get('Users');
                // $users = $usersTable->find()->where(['role' => 2])->all()->toArray();

                // foreach ($users as $user) {
                //     // testando se o usuário quer receber os emails
                //     if($user->optout_email != 1){
                //         // Separando as categorias dos parceiros
                //         $categoryPartner = $user->acting_cat;
                //         $arrayCategoryPartner = str_split($categoryPartner);
                //         $categoryPartnerSeparada = [];
                //         $pos = 0;
                //         if(strlen($categoryPartner) <= 1){
                //             $categoryPartnerSeparada[0] = $arrayCategoryPartner[0];
                //         }else{
                //             for($i = 0; $i < strlen($categoryPartner); $i++){
                //                 $j = $i+1;
                //                 if($arrayCategoryPartner[$i] != "," && $j < strlen($categoryPartner)){
                //                     $categoryPartnerSeparada[$pos] = $arrayCategoryPartner[$i];
                //                     if(isset($arrayCategoryPartner[$j]) && $arrayCategoryPartner[$j] != ","){
                //                         $categoryPartnerSeparada[$pos] = $categoryPartnerSeparada[$pos] . $arrayCategoryPartner[$j];
                //                         $i++;
                //                     }
                //                     $pos++;
                //                 }else if(isset($arrayCategoryPartner[$i]) && $arrayCategoryPartner[$i] != ","){
                //                     //Quando inserir categorias com apenas um numero ex: categoria: 1, 3
                //                     //A lógica não excluir a última categoria
                //                     $categoryPartnerSeparada[$pos] = $arrayCategoryPartner[$i];
                //                 }
                //             }
                //         }

                //         // for($i = 0; $i < count($categoryPartnerSeparada); $i++){
                //         //     if($user->acting_cat == 0 || $user->acting_cat == '' || $categoryPartnerSeparada[$i] == $category){
                //         //         $this->enviandoEmailsParaParceiros($user->name, $user->email, $user->id);
                //         //         $i = count($categoryPartnerSeparada);
                //         //     }
                //         // }
                //     }
                // }


                // Voltando para a página principal
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('A cotação não pode ser salva. Por favor, tente novamente.'));

        }

    }
  
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    //============================================
    //          ADICIONAR NOVA COTAÇÃO
    //============================================
    public function addNew(){
        $cotationsTable = TableRegistry::get('Cotations');
        $attstable = TableRegistry::get('CotationAttachments');
        $productsTable = TableRegistry::get('Products');

        $association = ['CotationServices', 'CotationProducts.CotationProductItems.Products', 'CotationAttachments'];

        //Preenchendo o array com todos os nome da tabela products
        $productsAll = $this->queryProductAll();
        $rowsProductsAll = $this->getCountProductAll();
        //$this->set(compact('productsAll','rowsProductsAll'));

        if ($this->request->is(['patch', 'post', 'put'])) {

            $requestData = $this->request->getData();
            if (isset($_FILES['anexo'])) {

                if (count($_FILES['anexo']['tmp_name']) > 0) { //verifica se ao menos 1 arquivo foi enviado
                    $totalarquivos = count($_FILES['anexo']['tmp_name']); // Irá contar o total de arquivos enviados
                    $uploaderro = 0;
                    $atts = [];

                    for ($q = 0; $q < $totalarquivos; $q++) {
                        $nomeOriginal = $_FILES['anexo']['name'][$q];
                        $nomedoarquivo = md5($_FILES['anexo']['name'][$q] . time() . rand(0, 999)) . substr($nomeOriginal, strrpos($nomeOriginal, '.'));
                        if (!move_uploaded_file($_FILES['anexo']['tmp_name'][$q], WWW_ROOT . '/uploads/cotations/' . $nomedoarquivo)) {
                            $uploaderro++;
                            continue;
                        }
                        $atts[] = ['name_original' => $nomeOriginal, 'name' => $nomedoarquivo];
                    }
                    $requestData['cotation_attachments'] = $atts;
                }

            }

            //Jogando os dados (arrays) enviados pela view nos respespectivos arrays
            $products = [];

            if (!empty($requestData['cotation_product']) && !empty($requestData['cotation_product']['cotation_product_items']) && count($requestData['cotation_product']['cotation_product_items']) > 0) {
                foreach ($requestData['cotation_product']['cotation_product_items'] as $key => $productItem) {
                    $requestData['cotation_product']['cotation_product_items'][$key]['quote'] = floatval(str_replace(['.', ','], ['', '.'], $productItem['quote']));
                    $products[$key] = $requestData['cotation_product']['cotation_product_items'][$key]['product'];
                }

                //TRATAR SE O ITEM QUE O USUÁRIO ESTÁ INSERINDO JÁ EXISTE NO BANCO
                //VALIDAR DE TODOS OS CAMPOS DE PRODUTOS SÃO OS MESMOS QUE ESTÃO SENDO INSERIDOS, CASO NÃO, ATUALIZAR.
                //PASSAR TODAS AS INSERÇÕES COMO MAÍSCULO OU MINÚSCULO PARA GARANTIR INTEGRIDADE

                //IMPLEMENTAR SE EXISTIR ATUALIZAR O REGISTRO EXISTENTE COM O NOVO A SER INSERIDO
                //AFIM DE CORRIGIR UM PROBLEMA QUE IMPEDI DE SALVAR A COTAÇÃO AO TENTAR SALVAR
                //UM PRODUTO EXISTENTE COM DADOS DIFERENTES

                //Testando se os itens já existem no banco através do nome
                // foreach ($products as $key => $productItem) {
                //     $productsData = $this->findProduct($productItem['item_name']);

                //     if(!empty($productsData)){
                //         // Adicionando o id do product existente na cotation_product_items
                //         $requestData['cotation_product']['cotation_product_items'][$key]['product_id'] = $productsData['id'];
                //         //unset($requestData['cotation_product']['cotation_product_items'][$key]['product']);

                //         if (array_key_exists('product', $requestData['cotation_product']['cotation_product_items'][$key])) {
                //            unset($requestData['cotation_product']['cotation_product_items'][$key]['product']);
                //         }
                //     }
                // }
            }

            if (!empty($requestData['cotation_service']) && count($requestData['cotation_service']) > 0) {
                $requestData ['cotation_service']['estimate'] = floatval(str_replace(['.', ','], ['', '.'], $requestData ['cotation_service']['estimate']));
            }

            $cotation = $cotationsTable->newEntity(null);
            $cotation = $cotationsTable->patchEntity($cotation, $requestData, ['associated' => $association]);

            $user = $this->request->getAttribute('identity');

            $cotation->status = 0; // status "aguardando parceiros"

            $cotation->user_id = $user->id;


            if ($cotation->type == 0) {
                $somaitens = 0;
                foreach ($cotation->cotation_product->cotation_product_items as $k => $item) {
                    $somaitens = $somaitens + ($item->quote * $item->quantity);
                }
                $cotation->cotation_product->estimate = $somaitens;
                $cotation->cotation_service = null;

            } elseif ($cotation->type == 1) {
                $cotation->cotation_product = null;
            }

            //ELIMINANDO OS ITENS QUE TIVER QTD 0 DA COTAÇÃO
            if (isset($cotation->cotation_product->cotation_product_items)){
                foreach ($cotation->cotation_product->cotation_product_items as $k => $item) {
                    if ($item->quantity == 0) {
                        unset($cotation->cotation_product->cotation_product_items[$k]);
                    }
                }
            }

            // if  ($this->request->is(['post'])){

            //     if ($cotationsTable->save($cotation)) {
            //         $this->Flash->success(__('Cotação salva com sucesso.'));
            //         //Envia email com os dados da cotação para todos os parceiros
            //        // $this->sendCotation($cotation);

            //         return $this->redirect(['action' => 'index']);
            //     }
            //     $this->Flash->error(__('A cotação não pode ser salva. Por favor, tente novamente.'));
            // }

            // date_default_timezone_set('America/Sao_Paulo');
            // $date = date('Y-m-d H:i');
            // $cotation->created = $date;
            if ($cotationsTable->save($cotation)) {
                $this->Flash->success(__('Cotação salva com sucesso.'));

                //PEGAR CATEGORIA PARA O SENDGRID
                $categorySendgrid = 0;
                if ($cotation->type  == 1) {
                    //SERVIÇO
                    $categorySendgrid = $cotation->cotation_service->category;

                }else{
                    //PRODUTO
                    $productsTable = TableRegistry::get('Products');
                    foreach ($cotation->cotation_product->cotation_product_items as $i => $itens){
                        $product = $productsTable->get($itens->product_id);
                        $categorySendgrid = $product->category_item_prod;
                    }
                }

                //ENVIANDO EMAILS
                //$this->sendGrid(1, $categorySendgrid);//Enviando email de aviso tipo 1: Avisando parceiros da categoria que há uma nova cotação

                // $usersTable = TableRegistry::get('Users');
                // $users = $usersTable->find()->where(['role' => 2])->all()->toArray();

                // foreach ($users as $user) {
                //     // testando se o usuário quer receber os emails
                //     if($user->optout_email != 1){
                //         // Separando as categorias dos parceiros
                //         $categoryPartner = $user->acting_cat;
                //         $arrayCategoryPartner = str_split($categoryPartner);
                //         $categoryPartnerSeparada = [];
                //         $pos = 0;
                //         if(strlen($categoryPartner) <= 1){
                //             $categoryPartnerSeparada[0] = $arrayCategoryPartner[0];
                //         }else{
                //             for($i = 0; $i < strlen($categoryPartner); $i++){
                //                 $j = $i+1;
                //                 if($arrayCategoryPartner[$i] != "," && $j < strlen($categoryPartner)){
                //                     $categoryPartnerSeparada[$pos] = $arrayCategoryPartner[$i];
                //                     if(isset($arrayCategoryPartner[$j]) && $arrayCategoryPartner[$j] != ","){
                //                         $categoryPartnerSeparada[$pos] = $categoryPartnerSeparada[$pos] . $arrayCategoryPartner[$j];
                //                         $i++;
                //                     }
                //                     $pos++;
                //                 }else if(isset($arrayCategoryPartner[$i]) && $arrayCategoryPartner[$i] != ","){
                //                     //Quando inserir categorias com apenas um numero ex: categoria: 1, 3
                //                     //A lógica não excluir a última categoria
                //                     $categoryPartnerSeparada[$pos] = $arrayCategoryPartner[$i];
                //                 }
                //             }
                //         }

                //         // for($i = 0; $i < count($categoryPartnerSeparada); $i++){
                //         //     if($user->acting_cat == 0 || $user->acting_cat == '' || $categoryPartnerSeparada[$i] == $category){
                //         //         $this->enviandoEmailsParaParceiros($user->name, $user->email, $user->id);
                //         //         $i = count($categoryPartnerSeparada);
                //         //     }
                //         // }
                //     }
                // }


                // Voltando para a página principal
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('A cotação não pode ser salva. Por favor, tente novamente.'));

        }
        else {
          //$cookies = $this->request->getCookieCollection();
          //$nome = $cookies->get('product_name');
          //print_r($_COOKIE);
          //$nome = $this->Cookie->read('product_name');
          $product_name = $this->request->getCookie('product_name');
          $this->set('product_name', $product_name);
          
          $categoria = $this->request->getCookie('categoria');
          $this->set('categoria', $categoria);
          
          $valor = $this->request->getCookie('valor');
          $this->set('valor', $valor);
          
          $direct_link = $this->request->getCookie('direct_link');
          $this->set('direct_link', '1');
          
          //echo $nome . $categoria . $valor . "lalalalal<br>";
          //$this->set(compact($coo);
        }

    }

    public function findProduct($productName) {
        if (empty($productName)) {
            return null;
        }else{

            $productsTable = TableRegistry::get('Products');
            $products = $productsTable->find()->where(['item_name' => $productName])->first();

            if ($products) {
                $productData = $products->toArray();
                return $productData;
            }else{
                return null;
            }
        }
    }

    public function queryProductAll() {
            $productsTable = TableRegistry::get('Products');
            $products = $productsTable->find()->all();
            $products->toArray();
            return $products;

            // if ($rows > 0) {
            //     for($i = 0; $i <= $rows; $i++ ){
            //         $productsNames[$i] = $products[$i]['item_name'];
            //     }

            //     return $productsNames;
            // }else return null;
    }

    public function getCountProductAll() {
        $productsTable = TableRegistry::get('Products');
        $products = $productsTable->find()->all();
        return $rows = $products->count();
}

    // public function queryProduct($productName) {
    //     if (empty($productName)) {
    //         return null;
    //     }else{

    //         $productsTable = TableRegistry::get('Products');

    //         $products = $productsTable->find()->where(['item_name LIKE' => "%{$productName}%"])->all();

    //         if ($products->count() > 0) {
    //             return $products->toArray();
    //         }else return null;
    //     }
    // }

/**
 * Edit method
 *
 * @param string|null $id Quotation id.
 * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
 */

public function edit($id = null){
    $cotationsTable = TableRegistry::get('Cotations');

    $association = ['CotationServices', 'CotationProducts.CotationProductItems.Products', 'CotationAttachments'];
    $cotation = $cotationsTable->get($id, [
        'contain' => $association
    ]);

    $cotations = $cotationsTable->find()->contain(['Users','Purchases', 'CotationServices', 'CotationProducts' => 'CotationProductItems.Products' ])
    ->leftJoinWith('CotationServices')
    ->leftJoinWith('Users')
    ->leftJoinWith('CotationProducts.CotationProductItems.Products')
    ->where(["Cotations.main_cotation_id" => $id, "Cotations.status <>" => 4]);


    $query = $this->request->getQueryParams();
    if (!empty($query['cotacao-search'])) {
        $cotations->andWhere(['title LIKE' => "%{$query['cotacao-search']}%"]);
    }

    $this->paginate = ['limit' => 300];

    $cotations = $this->paginate($cotations);

    //PASSANDO DADOS PARA O AUTO COMPLETE DOS ITENS
    if($cotation->type == 0){
        $productsTable = TableRegistry::get('Products');
        $productsAll = $productsTable->find()->where(['category_item_prod' => $cotation['cotation_product']['cotation_product_items'][0]['product']['category_item_prod']])->all()->toArray();
        $rowsProductsAll = count($productsAll);
        $this->set(compact('productsAll','rowsProductsAll'));
    }


    $this->set(compact('cotation'));
    //$this->set(compact('main_cotation'));
}
//====================================================
//            SALVANDO EDIÇÃO DA COTAÇÃO
//====================================================
public function editCotation(){
    $response = [
        'result' => 'failed'
    ];

    if ($this->request->is(['patch', 'post', 'put'])) {
        $requestData = $this->request->getData();

        //EDITANDO COTAÇÃO
        $cotationsTable = TableRegistry::get('Cotations');
        $c = $cotationsTable->get($requestData['id']);
        $c->title = $requestData['title'];
        $c->provider_qtd = intval($requestData['provider_qtd']);
        $c->objective =  $requestData['objective'];
        $c->deadline_date = $requestData['deadline_date'];
        $c->modified = date("Y-m-d h:i:s");
        $c->coverage = $requestData['coverage'];
        $result = $cotationsTable->find()->where(['main_cotation_id' => $requestData['id']])->all()->toArray();
        if($result){
            $c->status = 1;
        }else{
            $c->status = $requestData['status'];
        }


        //Verificar qual tipo da cotação
        if ($requestData['type'] == 0) {
            $cotationProductsTable = TableRegistry::get('CotationProducts');
            $cotationProductItemsTable = TableRegistry::get('CotationProductItems');
            $productsTable = TableRegistry::get('Products');

            //Editar cotação de produto
            $cp = $cotationProductsTable->get($requestData['cotation_product']['id']);
            $cp->cotation_id = $requestData['cotation_product']['cotation_id'];

            //Somatória de todos os orçamentos unitários de cada item
            $estimateCotationProd = 0;

            //Preparando cotation de itens
            foreach ($requestData['cotation_product']['cotation_product_items'] as $item) {
                if($item['id'] != "null"){
                    //Editar item
                    $cpi = $cotationProductItemsTable->get($item['id']);
                    $cpi->cotation_product_id = $item['cotation_product_id'];
                    $cpi->quantity = (int) $item['quantity'];
                    $cpi->quote = floatval(str_replace(['R$',' ','.', ','], ['','','', '.'], $item['quote']));
                    $cpi->product_id = $item['product_id'];
                    $cpi->provider_id = null;

                    //Somando estimativa de cotação de produto
                    $estimateCotationProd = $estimateCotationProd + ($cpi->quote * $cpi->quantity);

                    //Editar produto
                    $p = $productsTable->get($item['product_id']);
                    if(
                        $p->item_name != $item['products']['item_name'] &&
                        $p->model != $item['products']['model'] &&
                        $p->category_item_prod != $item['products']['category_item_prod'] &&
                        $p->manufacturer != $item['products']['manufacturer']
                        // && $p->sku == $item['products']['sku']
                    ){
                        //Adicionar como novo produto
                        $p_new = $productsTable->newEntity();
                        $p_new->item_name = $item['products']['item_name'];
                        $p_new->model = $item['products']['model'];
                        $p_new->category_item_prod = $item['products']['category_item_prod'];
                        $p_new->manufacturer = $item['products']['manufacturer'];
                        $p_new->sku = $item['products']['sku'];
                        if ($productsTable->save($p_new)) {
                            $response['result'] = 'success';
                        }else{
                            $response['errors'] = $p_new->errors();
                        }
                        $cpi->product_id = $p_new->id;
                    }else{
                        // editar o produto
                        $p->item_name = $item['products']['item_name'];
                        $p->model = $item['products']['model'];
                        $p->category_item_prod = $item['products']['category_item_prod'];
                        $p->manufacturer = $item['products']['manufacturer'];
                        $productsTable->save($p);
                    }
                    //Salvar item editado
                    if ($cotationProductItemsTable->save($cpi)) {
                        $response['result'] = 'success';
                    }else{
                        $response['errors'] = $cpi->errors();
                    }
                }else{
                    //Adicionar produto novo
                    $p_new = $productsTable->newEntity();
                    $p_new->item_name = $item['products']['item_name'];
                    $p_new->model = $item['products']['model'];
                    $p_new->category_item_prod = $item['products']['category_item_prod'];
                    $p_new->manufacturer = $item['products']['manufacturer'];
                    $p_new->sku = $item['products']['sku'];
                    if ($productsTable->save($p_new)) {
                        $response['result'] = 'success';
                    }else{
                        $response['errors'] = $p_new->errors();
                    }

                    //Adicionar item novo
                    $cpi_new = $cotationProductItemsTable->newEntity();
                    $cpi_new->cotation_product_id = $item['cotation_product_id'];
                    $cpi_new->quantity = $item['quantity'];
                    $cpi_new->quote = floatval(str_replace(['R$',' ','.', ','], ['','','', '.'], $item['quote']));
                    $cpi_new->product_id = $p_new->id;
                    $cpi_new->provider_id = null;

                    //Somando estimativa de cotação de produto
                    $estimateCotationProd += $cpi_new->quote;

                    if ($cotationProductItemsTable->save($cpi_new)) {
                        $response['result'] = 'success';
                    }else{
                        $response['errors'] = $cpi_new->errors();
                    }
                }
            }//Fim de foreach

            //Salvando cotação de produto
            $cp->estimate = $estimateCotationProd;
            if ($cotationsTable->save($c) && $cotationProductsTable->save($cp)) {
                $response['result'] = 'success';
            }else{
                $response['errors'] = [$c->errors(), $cp->errors()];
            }

        }else{
            //Editar cotação de serviço
            $cotationServicesTable = TableRegistry::get('CotationServices');
            $cs = $cotationServicesTable->get($requestData['cotation_service']['id']);
            $cs->description = $requestData['cotation_service']['description'];
            $cs->service_time = $requestData['cotation_service']['service_time'];
            $cs->category = $requestData['cotation_service']['category'];
            $cs->collection_type = $requestData['cotation_service']['collection_type'];
            $cs->expection_start = $requestData['cotation_service']['expection_start'];
            $cs->estimate = floatval(str_replace(['R$',' ','.', ','], ['','','', '.'], $requestData['cotation_service']['estimate']));

            //Salvando a edição da cotação de serviço
            if ($cotationsTable->save($c) && $cotationServicesTable->save($cs)) {
                $response['result'] = 'success';
               // $this->sendCotation($c);
            }else{
                $response['errors'] = $c->errors();
            }
        }
    }

    $this->set(compact('response','requestData'));
    $this->set('_serialize', 'response');
    $this->RequestHandler->renderAs($this, 'json');
}
public function addCotationAttachments(){
    $attstable = TableRegistry::get('CotationAttachments');

    if ($this->request->is(['patch', 'post', 'put'])){
        $uploaderro = 0;
        if (isset($_FILES['anexo'])) {

            if (count($_FILES['anexo']['tmp_name']) > 0) { //verifica se ao menos 1 arquivo foi enviado
                $totalarquivos = count($_FILES['anexo']['tmp_name']); // Irá contar o total de arquivos enviados

                $atts = [];

                for ($q = 0; $q < $totalarquivos; $q++) {
                    $nomeOriginal = $_FILES['anexo']['name'][$q];
                    $nomedoarquivo = md5($_FILES['anexo']['name'][$q] . time() . rand(0, 999)) . substr($nomeOriginal, strrpos($nomeOriginal, '.'));
                    if (!move_uploaded_file($_FILES['anexo']['tmp_name'][$q], WWW_ROOT . '/uploads/cotations/' . $nomedoarquivo)) {
                        $uploaderro++;
                        continue;
                    }
                    $att = $attstable->newEntity();
                    $att->name_original= $nomeOriginal;
                    $att->name= $nomedoarquivo;
                    $att->cotation_id = $_POST['cotation_id'];
                    if (!$attstable->save($att)) {
                        $uploaderro++;
                        continue;
                    }
                }
            }
        }
        echo $uploaderro;
        exit;
    }
}

public function  deleteAttachments(){
    $attstable = TableRegistry::get('CotationAttachments');

    if ($this->request->is(['get'])){
            $att = $attstable->get($this->request['pass']);
            $cotationId = $att['cotation_id'];
            $link = WWW_ROOT . '/uploads/cotations/'. $att['name'];
            $result = $attstable->delete($att);
            if ($result){
                unlink($link);
                $this->Flash->success(__('Anexo excluído.'));
                return $this->redirect(['action' => 'edit', $cotationId]);
            }else{
                $this->Flash->error(__('Erro ao excluir anexo. Por favor, tente novamente!'));
                return $this->redirect(['action' => 'edit', $cotationId]);
            }
    }
}

public function deleteItemCotation(){
    $response = [
        'result' => 'failed'
    ];

    if ($this->request->is("post")) {
        $cotationProductItemsTable = TableRegistry::get('CotationProductItems');
        $requestData = $this->request->getData();

        $result = $cotationProductItemsTable->get($requestData['idItem']);

        if($cotationProductItemsTable->delete($result)){
            $response['result'] = "success";
        }
    }

    $this->set(compact('response'));
    $this->set('_serialize', 'response');
    $this->RequestHandler->renderAs($this, 'json');
}

    /**
     * Delete method
     *
     * @param string|null $id Quotation id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    // public function delete($id = null){
    //     $this->request->allowMethod(['post', 'delete']);
    //     $quotation = $this->Quotation->get($id);
    //     if ($this->Quotation->delete($quotation)) {
    //     $this->Flash->success(__('The quotation has been deleted.'));
    //     } else {
    //     $this->Flash->error(__('The quotation could not be deleted. Please, try again.'));
    //     }

    //     return $this->redirect(['action' => 'index']);
    // }

    public function deleteAnexo(){
        $response = [
            'result' => 'failed',
        ];

        if ($this->request->is(['patch', 'post', 'put'])) {
            $cotationAttachmentsTable = TableRegistry::get('CotationAttachments');
            $requestData = $this->request->getData();
            $result = $cotationAttachmentsTable->get($requestData['id']);
            if($cotationAttachmentsTable->delete($result)){
                $response['result'] = 'success';
                unlink(WWW_ROOT . '/uploads/cotations/' . $result->name);
            }
        }
        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }

    // SALVANDO ANEXOS UTEIS DA COTAÇÃO
    public function saveAnexosCotation(){

        $response = [
            'result' => 'failed',
        ];

        if ($this->request->is(['patch', 'post', 'put'])) {

            $cotationAttachmentsTable = TableRegistry::get('CotationAttachments');

            $requestData = $this->request->getData();
            if (isset($_FILES['anexo'])) {

                if (count($_FILES['anexo']['tmp_name']) > 0) { //verifica se ao menos 1 arquivo foi enviado
                    $totalarquivos = count($_FILES['anexo']['tmp_name']); // Irá contar o total de arquivos enviados
                    $uploaderro = 0;
                    $atts = [];

                    for ($q = 0; $q < $totalarquivos; $q++) {
                        $nomeOriginal = $_FILES['anexo']['name'][$q];
                        $nomedoarquivo = md5($_FILES['anexo']['name'][$q] . time() . rand(0, 999)) . substr($nomeOriginal, strrpos($nomeOriginal, '.'));
                        if (!move_uploaded_file($_FILES['anexo']['tmp_name'][$q], WWW_ROOT . '/uploads/cotations/' . $nomedoarquivo)) {
                            $uploaderro++;
                            continue;
                        }
                        $atts = [
                            'name_original' => $nomeOriginal,
                            'name' => $nomedoarquivo,
                            'cotation_id' => $requestData['cotation-id'],
                        ];
                        $attachments = $cotationAttachmentsTable->newEntity(null);
                        $attachments = $cotationAttachmentsTable->patchEntity($attachments, $atts);
                        $response['erro'][] = $cotationAttachmentsTable->save($attachments);
                    }
                    //$requestData['cotation_attachments'] = $atts;
                }

                $response['result'] = 'success';

            }
            $response['data'] = $requestData;
        }

        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }

    // public function getImageProfile($user){

    //     //VALIDAÇÃO DA IMAGEM DE PERFIL PARA A BARRA DE MENU
    //     //Definindo o nome da imagem padrão que
    //     //irá aparecer caso o usuário não defina nenhuma.
    //     $ImageDefaultProfile = "profile-default.png";
    //     $imgProfileUser = "";
    //     //Testando se não há nenhuma imagem salva no banco.
    //     if (empty($user->img_profile)) {
    //         $imgProfileUser = "uploads/profile/".$ImageDefaultProfile;
    //     }else {
    //         $imgProfileUser = "uploads/profile/".$user->img_profile;
    //     }
    //     return $imgProfileUser;
    // }
    public function validarData($data){
        //O objetivo dessa função deve ser retornar true ou false se a data
        //informada já passou (false) ou se ainda vai chegar (true);
    }
}
