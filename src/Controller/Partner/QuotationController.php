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
class QuotationController extends AppController
{
    public function initialize()
    {

        parent::initialize();
        $this->viewBuilder()->setLayout('dashboard');

    }
    public function formatarData($data){
        $aux = "";
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
    public function index()
    {
        $user = $this->request->getAttribute('identity');
        $usersTable = TableRegistry::get('Users');
        $categoryPartner = $usersTable->find()->where(['id' => $user->id])->all()->first();
        $categoryPartner = $categoryPartner['acting_cat'];
        $arrayCategoryPartner = str_split($categoryPartner);
        $categoryPartnerSeparada = [];
        $pos = 0;
        if(strlen($categoryPartner) <= 1){
            $categoryPartnerSeparada[0] = $arrayCategoryPartner[0];
        }else{
            for($i = 0; $i < strlen($categoryPartner); $i++){
                $j = $i+1;
                if($arrayCategoryPartner[$i] != "," && $j < strlen($categoryPartner)){
                    $categoryPartnerSeparada[$pos] = $arrayCategoryPartner[$i];
                    if(isset($arrayCategoryPartner[$j]) && $arrayCategoryPartner[$j] != ","){
                        $categoryPartnerSeparada[$pos] = $categoryPartnerSeparada[$pos] . $arrayCategoryPartner[$j];
                        $i++;
                    }
                    $pos++;
                }else if(isset($arrayCategoryPartner[$i]) && $arrayCategoryPartner[$i] != ","){
                    //Quando inserir categorias com apenas um numero ex: categoria: 1, 3
                    //A lógica não excluir a última categoria
                    $categoryPartnerSeparada[$pos] = $arrayCategoryPartner[$i];
                }
            }
        }

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

        //Obter cotações enviadas pelo usuário
        $sendCotations= [];
            //Identifica o usuario logado
            $user = $this->request->getAttribute('identity');
            //Guarda os ids encontrados das cotações
            $cotationUserTable = TableRegistry::get('CotationUser');

            $sendCotations = $cotationUserTable->find()
            ->where(['user_id' => $user->id ]);
            $collection = new Collection($sendCotations);
            $sendCotations = $collection->extract('cotation_id')->toList();
             //É necessario que o array tenha pelo meno um valor para clausula where mais a frente
            array_push($sendCotations,-1);
        //Fim da lista de cotações enviadas

        $cotationsTable = TableRegistry::get('Cotations');
        // $cotations = $cotationsTable->find()->contain(['CotationServices', 'CotationProducts' => 'CotationProductItems']);

        //BUSCANDO COTAÇÕES PELA 1ª VEZ
        //PARA VERIFICAR SE A DATA DA COTAÇÃO
        if($categoryPartnerSeparada[0] == 0 || empty($categoryPartnerSeparada[0])){
            //$cotations = $cotationsTable->find()->where(['Cotations.main_cotation_id IS NULL'])->all()->toArray();
            $cotations = $cotationsTable->find()
                ->leftJoinWith('CotationServices')
                ->leftJoinWith('CotationProducts.CotationProductItems.Products')
                ->where([
                    'Cotations.id NOT IN' => $sendCotations,
                    'Cotations.main_cotation_id IS NULL'
                ])
                ->order(['Cotations.id' => 'DESC'])
                ->toArray();
        }else{
            $wheres = [];
            $j = 0;
            for ($i = 0; $i < count($categoryPartnerSeparada); $i++) {
                $wheres[$j] = [
                    'Cotations.type' => 1,
                    'CotationServices.category' => $categoryPartnerSeparada[$i],
                ];
                $wheres[$j+1] = [
                    'Cotations.type' => 0,
                    'Products.category_item_prod' => $categoryPartnerSeparada[$i]
                ];
                $j += 2;
            }

            $cotations = $cotationsTable->find()
            ->order(['Cotations.id' => 'DESC'])
            ->leftJoinWith('CotationServices')
            ->leftJoinWith('CotationProducts.CotationProductItems.Products')
            ->where([
                'Cotations.id NOT IN' => $sendCotations,
                'Cotations.main_cotation_id IS NULL',
                'OR' => $wheres
            ])->toArray();
        }

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

        //BUSCANDO COTAÇÕES PELA 2ª VEZ
        //PEGANDO AS COTAÇÕES AGORA COM OS STATUS ATUALIZADOS
        if($categoryPartnerSeparada[0] == 0 || empty($categoryPartnerSeparada[0])){
            // $cotations = $cotationsTable->find()->where(['Cotations.main_cotation_id IS NULL'])->all()->toArray();
            $cotations = $cotationsTable->find()
                ->leftJoinWith('CotationServices')
                ->leftJoinWith('CotationProducts.CotationProductItems.Products')
                ->order(['Cotations.id' => 'DESC'])
                ->where([
                    'Cotations.id NOT IN' => $sendCotations,
                    'Cotations.main_cotation_id IS NULL'
                ])->toArray();
        }else{
            $wheres = [];
            $j = 0;
            for ($i = 0; $i < count($categoryPartnerSeparada); $i++) {
                $wheres[$j] = [
                    'Cotations.type' => 1,
                    'CotationServices.category' => $categoryPartnerSeparada[$i],
                ];
                $wheres[$j+1] = [
                    'Cotations.type' => 0,
                    'Products.category_item_prod' => $categoryPartnerSeparada[$i]
                ];
                $j += 2;
            }

            $cotations = $cotationsTable->find()
                ->leftJoinWith('CotationServices')
                ->leftJoinWith('CotationProducts.CotationProductItems.Products')
                ->where([
                    'Cotations.id NOT IN' => $sendCotations,
                    'Cotations.main_cotation_id IS NULL',
                    'OR' => $wheres
                ])
                ->order(['Cotations.id' => 'DESC'])
                ->all()->toArray();
        }

        $cotationsIds = [];
        $cotationProvidersTable = TableRegistry::get('CotationProviders');
        foreach ($cotations as $k => $c) {
            //Aproveitando o foreach para:
            //Verificando se o parceiro já fez um envio desta cotação, caso tenha, não exibi-la novamente.
            if($c['status'] == 1){
                $result = $cotationsTable->find()->where(['user_id' => $user->id, 'main_cotation_id' =>  $c['id']])->order(['Cotations.id' => 'DESC'])->all()->toArray();
                if(count($result) > 0){
                    unset($cotations[$k]);
                }else{
                    //Criando array de ids para o paginator (Já estava aqui)
                    $cotationsIds[] = $c['id'];
                }
            }else{
                //Criando array de ids para o paginator (Já estava aqui)
                $cotationsIds[] = $c['id'];
            }
        }

        if(count($cotations) > 0 ) {
            $cotations = $cotationsTable->find()->order(['Cotations.id' => 'DESC'])->contain(['CotationServices', 'CotationProducts' => 'CotationProductItems'])->where(['Cotations.id IN' => $cotationsIds]);

            $query = $this->request->getQueryParams();
            if (!empty($query['cotacao-search'])) {
                $cotations->andWhere(['title LIKE' => "%{$query['cotacao-search']}%"]);
            }
            $this->paginate = ['limit' => 300];
            $cotations = $this->paginate($cotations);
        }
        //$this->enviandoEmailsParaCliente('JUAN JC', 'juancleiton30@gmail.com', 'ANTONIO', 2, 'produto');
        $this->set(compact('cotations'));
    }

    private function buscarCotations($cotationsTable){
        //Método para a index

    }
    /**
     * View method
     *
     * @param string|null $id Quotation id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null){
        $usersTable = TableRegistry::get('Users');
        $cotationsTable = TableRegistry::get('Cotations');

        $cotation = $cotationsTable->get($id, [
            'contain' => [
                'CotationServices',
                'CotationProducts.CotationProductItems.Products',
                'CotationAttachments'
            ],
        ]);

        $user = $this->request->getAttribute('identity');
        $user = $usersTable->find()->where(['id' => $user->id])->first();
        $this->set('cotation', $cotation);
        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add(){
        $quotation = $this->Quotation->newEntity();
        if ($this->request->is('post')) {
            $quotation = $this->Quotation->patchEntity($quotation, $this->request->getData());
            if ($this->Quotation->save($quotation)) {
                $this->Flash->success(__('The quotation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The quotation could not be saved. Please, try again.'));
        }
        $this->set(compact('quotation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Quotation id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $cotationsTable = TableRegistry::get('Cotations');
        $cotationProvidersTable = TableRegistry::get('CotationProviders');
        $cotationServicesTable = TableRegistry::get('CotationServices');

        $association = ['CotationServices', 'CotationProducts.CotationProductItems.Products', 'CotationAttachments'];
        $cotation = $cotationsTable->get($id, [
            'contain' => $association
        ]);

        //$cotation['cotation_providers'] = $cotationProvidersTable->find()->where(['cotation_id' => $cotation->id])->all()->toArray();
        $cotation['cotation_providers'] = $cotationProvidersTable->find()->contain(['Providers'])
        ->leftJoinWith('Providers')
        ->where(['cotation_id' => $cotation->id])->all()->toArray();

        $aux = 0;
        $arrayProviderItems = $cotation['cotation_providers'];
        foreach ($cotation['cotation_providers'] as $key => $value) {
            $arrayProviderItems[$key] = array(
                'cotation_providers' => $cotation['cotation_providers'][$key],
                'item' => []
            );
            foreach ($cotation->cotation_product->cotation_product_items as $k => $item) {
                if($value->provider_id == $item->provider_id){
                    $arrayProviderItems[$key]['item'][] = $item;
                    //$arrayProviderItems[$key]['item'][$aux] = $item;
                    $aux++;
                }
            }
        }


        if($cotation['type'] == 1){
            $cs = $cotationServicesTable->find()
            ->where(['cotation_id' => $cotation['id']])->all()->toArray();
            $cotation['cotation_service'] = $cs;
        }
        $this->set(compact('cotation', 'arrayProviderItems'));
        //$this->set(compact('main_cotation'));

    }

    public function editCotationProductSend(){
        $response = [
            'result' => 'failed'
        ];
        if ($this->request->is(['patch', 'post', 'put'])) {
            $requestData = $this->request->getData();

            if($requestData['type'] == 0){
                $cotationProvidersTable = TableRegistry::get('CotationProviders');
                $cotationProductItemsTable = TableRegistry::get('CotationProductItems');


                    // $result = $cotationProvidersTable->get($r['cotation_provider_id']);
                $result = $cotationProvidersTable->get($requestData['cotation_provider_id']);
                $result->cost = floatval(str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], $requestData['cost']));
                $result->deadline = $requestData['deadline'];
                $cotationProvidersTable->save($result);

                foreach ($requestData['itens'] as $k => $item) {
                    $res = $cotationProductItemsTable->get($item['cotation_product_item_id']);
                    $res->quote = floatval(str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], $item['quote']));
                    $res->quantity = $item['quantity'];
                    $res->link_item = $item['link_item'];
                    $cotationProductItemsTable->save($res);
                }

                    $response['result'] = 'success';
                //}
            }else if($requestData['type'] == 1){
                $cotationServicesTable = TableRegistry::get('CotationServices');
                $result = $cotationServicesTable->get($requestData['cotation_service_id']);
                $result->estimate = floatval(str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], $requestData['estimate']));
                $result->expectation_start = $requestData['expectation_start'];
                $result->service_time = $requestData['service_time'];

                if($cotationServicesTable->save($result)){
                    $cotationsTable = TableRegistry::get('Cotation');
                    $cotation = $cotationsTable->get($result->cotation_id);
                    //$this->sendCotation($cotation);
                    $response['result'] = 'success';

                }
            }

        }

        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }

    //====================================================
    //            SALVANDO EDIÇÃO DA COTAÇÃO
    //====================================================
    public function editCotation()
    {
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
            $c->status = 1;

            //EDITANDO ARQUIVO SE EXISTIR
            // if (isset($_FILES['anexo'])) {
            //     if (count($_FILES['anexo']['tmp_name']) > 0) { //verifica se ao menos 1 arquivo foi enviado
            //         $totalarquivos = count($_FILES['anexo']['tmp_name']); // Irá contar o total de arquivos enviados
            //         $uploaderro = 0;
            //         $atts = [];

            //         for ($q = 0; $q < $totalarquivos; $q++) {
            //             $nomeOriginal = $_FILES['anexo']['name'][$q];
            //             $nomedoarquivo = md5($_FILES['anexo']['name'][$q] . time() . rand(0, 999)) . substr($nomeOriginal, strrpos($nomeOriginal, '.'));
            //             if (!move_uploaded_file($_FILES['anexo']['tmp_name'][$q], WWW_ROOT . '/uploads/cotations/' . $nomedoarquivo)) {
            //                 $uploaderro++;
            //                 continue;
            //             }
            //             $atts[] = ['name_original' => $nomeOriginal, 'name' => $nomedoarquivo];
            //         }
            //         $requestData['cotation_attachments'] = $atts;
            //     }
            // }

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
                    if ($item['id'] != "null") {
                        //Editar item
                        $cpi = $cotationProductItemsTable->get($item['id']);
                        $cpi->cotation_product_id = $item['cotation_product_id'];
                        $cpi->quantity = $item['quantity'];
                        $cpi->quote = floatval(str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], $item['quote']));
                        $cpi->product_id = $item['product_id'];
                        $cpi->provider_id = null;

                        //Somando estimativa de cotação de produto
                        $estimateCotationProd += $cpi->quote;

                        //Editar produto
                        $p = $productsTable->get($item['product_id']);
                        if (
                            $p->item_name == $item['products']['item_name'] &&
                            $p->model == $item['products']['model'] &&
                            $p->category_item_prod == $item['products']['category_item_prod'] &&
                            $p->manufacturer == $item['products']['manufacturer'] &&
                            $p->sku == $item['products']['sku']
                        ) {
                            //Mesmo produto
                        } else {
                            //Adicionar como novo produto
                            $p_new = $productsTable->newEntity();
                            $p_new->item_name = $item['products']['item_name'];
                            $p_new->model = $item['products']['model'];
                            $p_new->category_item_prod = $item['products']['category_item_prod'];
                            $p_new->manufacturer = $item['products']['manufacturer'];
                            $p_new->sku = $item['products']['sku'];
                            if ($productsTable->save($p_new)) {
                                $response['result'] = 'success';
                            } else {
                                $response['errors'] = $c->errors();
                            }
                            $cpi->product_id = $p_new->id;
                        }
                        //Salvar item editado
                        if ($cotationProductItemsTable->save($cpi)) {
                            $response['result'] = 'success';
                        } else {
                            $response['errors'] = $c->errors();
                        }
                    } else {
                        //Adicionar produto novo
                        $p_new = $productsTable->newEntity();
                        $p_new->item_name = $item['products']['item_name'];
                        $p_new->model = $item['products']['model'];
                        $p_new->category_item_prod = $item['products']['category_item_prod'];
                        $p_new->manufacturer = $item['products']['manufacturer'];
                        $p_new->sku = $item['products']['sku'];
                        if ($productsTable->save($p_new)) {
                            $response['result'] = 'success';
                        } else {
                            $response['errors'] = $c->errors();
                        }

                        //Adicionar item novo
                        $cpi_new = $cotationProductItemsTable->newEntity();
                        $cpi_new->cotation_product_id = $item['cotation_product_id'];
                        $cpi_new->quantity = $item['quantity'];
                        $cpi_new->quote = floatval(str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], $item['quote']));
                        $cpi_new->product_id = $p_new->id;
                        $cpi_new->provider_id = null;

                        //Somando estimativa de cotação de produto
                        $estimateCotationProd += $cpi_new->quote;

                        if ($cotationProductItemsTable->save($cpi_new)) {
                            $response['result'] = 'success';
                        } else {
                            $response['errors'] = $c->errors();
                        }
                    }
                } //Fim de foreach

                //Salvando cotação de produto
                $cp->estimate = $estimateCotationProd;
                if ($cotationsTable->save($c) && $cotationProductsTable->save($cp)) {
                   // $this->sendCotation($c);
                    $response['result'] = 'success';
                } else {
                    $response['errors'] = $c->errors();
                }
            } else {
                //Editar cotação de serviço
                $cotationServicesTable = TableRegistry::get('CotationServices');
                $cs = $cotationServicesTable->get($requestData['cotation_service']['id']);
                $cs->description = $requestData['cotation_service']['description'];
                $cs->service_time = $requestData['cotation_service']['service_time'];
                $cs->category = $requestData['cotation_service']['category'];
                $cs->collection_type = $requestData['cotation_service']['collection_type'];
                $cs->expection_start = $requestData['cotation_service']['expection_start'];
                $cs->estimate = floatval(str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], $requestData['cotation_service']['estimate']));

                //Salvando a edição da cotação de serviço
                if ($cotationsTable->save($c) && $cotationServicesTable->save($cs)) {
                    $response['result'] = 'success';
                   // $this->sendCotation($c);
                } else {
                    $response['errors'] = $c->errors();
                }
            }
        }

        $this->set(compact('response', 'requestData'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }

    public function deleteItemCotation()
    {
        $response = [
            'result' => 'failed'
        ];

        if ($this->request->is("post")) {
            $requestData = $this->request->getData();
            $response['result'] = "success";
            $response['dado'] = $requestData;
        }

        $this->set(compact('response', 'requestData'));
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
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $quotation = $this->Quotation->get($id);
        if ($this->Quotation->delete($quotation)) {
            $this->Flash->success(__('The quotation has been deleted.'));
        } else {
            $this->Flash->error(__('The quotation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function findProviderByCnpj()
    {
        $response = [
            'result' => 'failed'
        ];

        $cnpj = $this->request->getQuery('cnpj', null);
        if ($cnpj !== null) {
            $providersTable = TableRegistry::get('Providers');
            $provider = $providersTable->find()->where(['cnpj' => $cnpj, 'active' => 1])->first();

            if ($provider) {
                $response['result'] = 'success';
                $response['data'] = $provider->toArray();
            } else {
                $response['result'] = 'notfound';
            }
        }

        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }

    //=====================================================================
    //              SALVANDO FORNECEDOR
    //=====================================================================

    public function addProvider(){
        $response = [
            'result' => 'failed',
            'idProvider' => 'null',
            'data' => '',
        ];

        if ($this->request->is('post')) {
            $providersTable = TableRegistry::get('Providers');

            $requestData = $this->request->getData();
            $errors = 0;

            if (!empty($requestData)) {
                $provider = $requestData;
                $cnpj = $provider['cnpj'];

                $p = $providersTable->find()->where(['cnpj' => $cnpj])->first();
                if ($p) {
                    if($p['active'] != 1) {
                        $response['result'] = 'suspenso';
                    }else{
                        $response['idProvider'] = $p->id;
                        $response['result'] = 'success';
                    }
                } else {
                    $newProvider = $providersTable->newEntity(null);
                    unset($provider['id']);
                    $newProvider = $providersTable->patchEntity($newProvider, $provider);
                    $newProvider['active'] = 1;
                    if ($result = $providersTable->save($newProvider)) {
                        $response['idProvider'] = $result->id;
                        $response['result'] = 'success';
                    } else {
                        $errors++;
                    }
                }
            }
        }
        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }

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

    // SALVANDO ANEXOS UTEIS DA COTAÇÃO PARA CADA FORNECEDOR
    public function saveAnexosFornecedor(){

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
                            'provider_id' => $requestData['provider-id'],
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

    //=====================================================================
    //              SALVANDO ENVIO DA COTAÇÃO DE PRODUTO
    //=====================================================================

    public function addCotationProduct(){

        $response = [
            'result' => 'failed'
        ];

        if ($this->request->is('post')) {
            $cotationsTable = TableRegistry::get('Cotations');

            $cotationAssociation = [
                'CotationServices',
                'CotationProducts.CotationProductItems.Products',
                'CotationProviders',
                //'CotationAttachments',
            ];

            $requestData = $this->request->getData();
            $user = $this->request->getAttribute('identity');

            $errors = 0;
            $requestData['cotation_product']['estimate'] = 0;

            foreach ($requestData['cotation_product']['cotation_product_items'] as $k => $item) {
                $quote = floatval(str_replace(['R$ ', '.', ','], ['', '', '.'], $item['quote']));
                $requestData['cotation_product']['cotation_product_items'][$k]["quote"] = $quote;
                $quantity = (int) $item['quantity'];
                $requestData['cotation_product']['estimate'] += $quote * $quantity;
            }

            foreach ($requestData['cotation_providers'] as $k => $item) {
                $requestData['cotation_providers'][$k]['cost'] = floatval(str_replace(['R$ ', '.', ','], ['', '', '.'], $item['cost']));
                $requestData['cotation_providers'][$k]['provider_id'] = (int) $item['provider_id'];
                $requestData['cotation_providers'][$k]['deadline'] = (int) $item['deadline'];
                $requestData['cotation_providers'][$k]['user_id'] = (int) $user->id;
            }

            if(!empty($requestData['id'])){
                $cotationProductsTable = TableRegistry::get('CotationProducts');

                $c_id = (int) $requestData['id'];
                $c = $cotationsTable->get($c_id);
                $response['idCotation'] = $c->id;
                $response['data'] = $requestData;
                // $c->provider_qtd = $c->provider_qtd + 1;
                // $cotationsTable->save($c);

                $result = $cotationProductsTable->find()->where(['cotation_id' => $c_id])->all()->toArray();
                $cotation_product_id = $result[0]['id'];

                if(!empty($c)){
                    $cotationProvidersTable = TableRegistry::get('CotationProviders');
                    // $productsTable = TableRegistry::get('Products');
                    $ctpd = $cotationProductsTable->newEntity();
                    $requestData['cotation_product']['cotation_id'] = (int) $c_id;
                    $ctpd = $cotationProductsTable->patchEntity($ctpd, $requestData['cotation_product'], ['associated' => 'CotationProductItems.Products']);

                    foreach ($requestData['cotation_providers'] as $k => $item) {
                        $requestData['cotation_providers'][$k]['cotation_id'] = $c_id;
                        $ctpv = $cotationProvidersTable->newEntity();
                        $ctpv = $cotationProvidersTable->patchEntity($ctpv, $requestData['cotation_providers'][$k]);
                        $cotationProvidersTable->save($ctpv);
                    }

                    if ($cotationProductsTable->save($ctpd)){
                        $response['result'] = 'success';
                        // //Excluindo itens que tem qtd 0
                        $cotationProductItemsTable = TableRegistry::get('CotationProductItems');
                        $result = $cotationProductItemsTable->find()->where(['quantity' => 0])->all()->toArray();
                        if( count($result) > 0 ){
                            $cotationProductItemsTable->deleteAll(['quantity' => 0]);
                        }
                        //Implementação temporária para manter apenas
                        //Um registro na cotation_prodiver referente a cotation
                        //Porém com itens de direferntes fornecedores
                        $cotationProductItemsTable = TableRegistry::get('CotationProductItems');
                        $result = $cotationProductItemsTable->find()->where(['cotation_product_id' => $ctpd->id])->all()->toArray();
                        foreach ($result as $k => $r) {
                            $c = $cotationProductItemsTable->get($r->id);
                            $c->cotation_product_id = $cotation_product_id;
                            $cotationProductItemsTable->save($c);
                        }
                        $cotationProductsTable->deleteAll(['id' => $ctpd->id]);
                    }
                }

            }else{
                $c = $cotationsTable->newEntity();
                $c = $cotationsTable->patchEntity($c, $requestData, ['associated' => $cotationAssociation]);
                $c->status = 0;
                //$c->status = 0; status "aguardando parceiros"
                //$c->status = 1; status "Em andamento"
                $c->user_id = $user->id;
                $c->provider_qtd = 1;
                //$c->modified = date("d-m-Y h:i:s");
                if ($cotationsTable->save($c)) {
                    $response['idCotation'] = $c->id;

                    //Mudando status da cotação que recebeu um envio
                    $cotation = $cotationsTable->get($c->main_cotation_id);
                    $cotation->status = 1;

                    if ($cotationsTable->save($cotation)) {
                        $response['result'] = 'success';
                        $response['data'] = $requestData;
                        //Salvando a relação de n parceiros para n cotações
                        $this->saveCotationUser($user->id,  $c->id);

                        // //Excluindo itens que tem qtd 0
                        $cotationProductItemsTable = TableRegistry::get('CotationProductItems');
                        $result = $cotationProductItemsTable->find()->where(['quantity' => 0])->all()->toArray();
                        if( count($result) > 0 ){
                            $cotationProductItemsTable->deleteAll(['quantity' => 0]);
                        }

                        //Enviando e-mails para os clientes
                        $usersTable = TableRegistry::get('Users');
                        $users_c = $usersTable->find()->where(['id' => $cotation->user_id])->first();

                        $userId = $users_c['id'];
                        //$email_to = $users_c['email'];
                        $namePartner = $user['name'];
                        $cotationIdClient = $cotation->id;
                        $typeCotation = 'produto';
                        $response['data'] = [$userId, $namePartner, $cotationIdClient, $typeCotation];
                        $curl = curl_init();
                        // Seta algumas opções
                        curl_setopt_array($curl, [
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_URL => 'https://ngproc.com.br/sendgrid/index.php',
                            CURLOPT_POST => 1,
                            CURLOPT_POSTFIELDS => [
                                'userId' =>  $userId,
                                'namePartner' => $namePartner,
                                'cotationIdClient' => $cotationIdClient,
                                'typeCotation' =>  $typeCotation,
                                'type' =>  2, //WarningNewAnswers
                            ]
                        ]);
                        // Envia a requisição e salva a resposta
                        curl_exec($curl);
                        // Fecha a requisição e limpa a memória
                        curl_close($curl);

                    }else{
                        $response['errors'] = $c->errors();
                    }
                }else {
                    $response['errors'] = $c->errors();
                }
            }

        }


        $user = $this->request->getAttribute('identity');
        // Cria o cURL

        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }
    private function sendCotation($cotation){

        if ($cotation->type  == 1) {
            //SERVIÇO
            $template = 'sendcotationsservice';
            $category = $cotation->cotation_service->category;
            $this->sendMail($category, $template, $cotation);

        }else{
            //PRODUTO
            $template = 'sendcotaitionsproducts';
            $arrItens  = [];
            //Agrupando itens por categoria
            $productsTable = TableRegistry::get('Products');

            foreach ($cotation->cotation_product->cotation_product_items as $i => $itens){
                $product = $productsTable->get($itens->product_id);
                $arrItens[$product->category_item_prod][$i] = $itens;
                $arrItens[$product->category_item_prod][$i]['name'] = $product->item_name;
            }
            $cont=0;
            //Não é possível fazer for no relatório
            $itensHtml = "";
            foreach ($arrItens as $j => $item){
                    $itensHtml .= "
                    <tr>
                        <td>" . $item[$cont]['name']. "</td>
                        <td>" . $item[$cont]['quantity'] . "</td>
                        <td>" . number_format($item[$cont]['quote'], 2, ',', '.')  . "</td>
                    </tr>
                    ";
                $this->sendMail($j, $template, $cotation, $itensHtml);
                $cont++;
            }
        }
    }

    // private function sendMail($category, $template, $cotation, $itens =""){

    //     $usersTable = TableRegistry::get('Users');
    //     $email = new Email(['from' => 'mailservice@ngproc.com.br', 'EmailTransport' => 'default']);
    //     //filtra usuario por categoria
    //     $users = $usersTable->find('all')->where(['role' =>  $category]);

    //     foreach ($users as $user) {
    //         //Valida email
    //         if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) continue;

    //         $email->template($template)
    //             ->viewVars(['cotation' =>  $cotation, 'itens' => $itens])
    //             ->from(['mailservice@ngproc.com.br' => 'NGProc'])
    //             ->to($user->email)
    //             ->subject('NGProc - Nova cotação')
    //             ->emailFormat('html');

    //         $email->send();
    //     }
    // }
    private function saveCotationUser($userid, $cotationid){
        $cotationUserTable = TableRegistry::get('CotationUser');
        $cotationUser = $cotationUserTable->newEntity();
        $cotationUser->user_id = $userid;
        $cotationUser->cotation_id = $cotationid;
        $cotationUserTable->save($cotationUser);
    }

    private function cleanCotationIds(&$cotation) {
        unset($cotation['id']);
        unset($cotation['user_id']);
        unset($cotation['created']);


        if (!empty($cotation['cotation_product'])) {
            unset($cotation['cotation_product']['id']);

            // foreach ($cotation['cotation_product']['cotation_product_items'] as $key => $cpi) {
            //     unset($cotation['cotation_product']['cotation_product_items'][$key]['product']);
            // }
        }
        if (!empty($cotation['cotation_service'])) {
            unset($cotation['cotation_service']['id']);
        }
    }

    //=====================================================================
    //              SALVANDO ENVIO DA COTAÇÃO DE SERVIÇO
    //=====================================================================
    public function addCotationService(){
        $response = [
            'result' => 'failed'
        ];

        if ($this->request->is('post')) {

            $cotationsTable = TableRegistry::get('Cotations');
            $cotationServicesTable = TableRegistry::get('CotationServices');
            $cotationProvidersTable = TableRegistry::get('CotationProviders');

            $cotationAssociation = [
                'CotationServices',
                'CotationProducts.CotationProductItems.Products',
                //'CotationAttachments',
                'CotationProviders',
                //'CotationUser'
            ];

            $requestData = $this->request->getData();
            $user = $this->request->getAttribute('identity');

            $id = (int) $requestData['main_cotation_id'];

            $cotation = $cotationsTable->get($id, ['contain' => $cotationAssociation])->toArray();

            if (!empty($cotation)) {

                if(!empty($requestData['id'])){

                    $c_id = (int) $requestData['id'];
                    $c = $cotationsTable->get($c_id);
                    $c->provider_qtd = $c->provider_qtd + 1; //Quando a cotação é um envio de parceiro. este campo se torna a contagem de quantos fornecedores foram enviados na cotação
                    $cotationsTable->save($c);

                    $requestData['cotation_service']['orcamento_env'] = $this->convertMoneyToFloat($requestData['cotation_service']["orcamento_env"]);
                    $ctsv = $cotationServicesTable->newEntity();
                    $ctsv->description = $requestData['cotation_service']['description'];
                    $ctsv->category = $requestData['cotation_service']['category'];
                    $ctsv->collection_type = $requestData['cotation_service']['collection_type'];
                    $ctsv->estimate = $requestData['cotation_service']['orcamento_env'];
                    $ctsv->expectation_start = $requestData['cotation_service']['exp_start_servico'];
                    $ctsv->service_time = $requestData['cotation_service']['tempo_estimado'];
                    $ctsv->provider_id = (int) $requestData['cotation_service']['provider_id'];
                    $ctsv->cotation_id = (int) $requestData['cotation_service']['cotation_id'];

                    //Salvando Cotation Provider
                    $cot_provider = $cotationProvidersTable->newEntity();
                    $cot_provider->cotation_id = (int) $requestData['cotation_service']['cotation_id'];
                    $cot_provider->user_id = $user->id;
                    $cot_provider->provider_id = (int) $requestData['cotation_service']["provider_id"];

                    if ($cotationServicesTable->save($ctsv) && $cotationProvidersTable->save($cot_provider)) {
                           $this->saveCotationUser($user->id,  $c->id);
                        $response['result'] = 'success';
                        $response['data'] = $ctsv;
                    }

                }else{

                    $this->cleanCotationIds($cotation);

                    $cotation['main_cotation_id'] = $id;

                    $c = $cotationsTable->newEntity();
                    $c = $cotationsTable->patchEntity($c, $cotation, ['associated' => $cotationAssociation]);

                    //$c->main_cotation_id = $requestData["cotation"]['id'];
                    $user = $this->request->getAttribute('identity');
                    $c->status = 0;
                    //$c->status = 0; status "aguardando parceiros"
                    //$c->status = 1; status "Em andamento"
                    $c->user_id = $user->id;
                    $c->provider_qtd = 1;

                    // //Atualizando dados
                    $c['cotation_service']['service_time'] = $requestData['cotation_service']["tempo_estimado"];
                    $c['cotation_service']['expectation_start'] = $requestData['cotation_service']["exp_start_servico"];
                    $c['cotation_service']['estimate'] = $this->convertMoneyToFloat($requestData['cotation_service']["orcamento_env"]);
                    $c['cotation_service']['provider_id'] = $requestData['cotation_service']["provider_id"];


                    if ($cotationsTable->save($c)) {
                        $response['idCotation'] = $c->id;

                        //Salvando Cotation Provider
                        $cot_provider = $cotationProvidersTable->newEntity();
                        $cot_provider->cotation_id = $c->id;
                        $cot_provider->user_id = $user->id;
                        $cot_provider->provider_id = $requestData['cotation_providers']['provider_id'];

                        //Mudando status da cotação que recebeu um envio
                        $cotation = $cotationsTable->get($c->main_cotation_id);
                        $cotation->status = 1;

                        if ($cotationsTable->save($cotation) && $cotationProvidersTable->save($cot_provider)) {
                            //Salvando a relação de n parceiros para n cotações
                            $this->saveCotationUser($user->id,  $c->id);
                            $response['result'] = 'success';

                            //Enviando e-mails para os clientes
                            $usersTable = TableRegistry::get('Users');
                            $users_c = $usersTable->find()->where(['id' => $cotation->user_id])->first();
                            // $nameCliente = $users_c['name'];
                            // $email_to = $users_c['email'];
                            // $nameParceiro = $user['name'];
                            // $cotationClienteId = $cotation->id;
                            // $type = 'serviço';
                            //$response['data'] = [$nameCliente, $email_to, $nameParceiro, $cotationClienteId, $type];
                            // $this->enviandoEmailsParaCliente($nameCliente, $email_to, $nameParceiro, $cotationClienteId, $type);
                            // if($users_c['optout_email'] != 1){
                            //     $this->enviandoEmailsParaCliente($users_c['name'], $users_c['email'], $user['name'], $cotation->id, 'serviço', $users_c['id']);
                            // }
                        }
                    } else {
                        $response['errors'] = $c->errors();
                    }
                }

            }
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


    private function convertMoneyToFloat($value) {
        return floatval(str_replace(['R$ ', '.', ','], ['', '', '.'], $value));
    }
    //=====================================================================
    //             SCRIPT DE TESTE DE INSERÇÃO SIMULTANEAS
    //=====================================================================
    // public function insertCotations(){
    //     $cotationsTable = TableRegistry::get('Cotations');
    //     $cotationsProductsTable = TableRegistry::get('CotationProducts');
    //     $cotationsProductsItemsTable = TableRegistry::get('CotationProductItems');
    //     $cont = 1;
    //     //Salva Cotação
    //     //Quantas cotações?
    //     $cotationqtd = 12;
    //     $errors = 0;
    //     for ($i=0; $i <  $cotationqtd; $i++) {
    //         $cotation = $cotationsTable->newEntity();
    //         $cotation->title = "Cotação $cont";
    //         $cotation->type  = 0;
    //         $cotation->provider_qtd = 4;
    //         $cotation->objective = "1";
    //         $cotation->deadline_date = "Tempo indeterminado";
    //         $cotation->status = 0;
    //         $cotation->coverage= "Nacional";
    //         $cotation->main_cotation_id= 296;
    //         $cotation->user_id = 19;  // ATENÇÃO - PRECISA EXITIR O USUARIO NO BANCO
    //         $cont++;

    //         if (!$cotationsTable->save($cotation)){
    //             $errors++;
    //         }
    //         //Salva produto cotacao
    //         $cotationsProduct = $cotationsProductsTable->newEntity();
    //         $cotationsProduct->cotation_id = $cotation->id;
    //         $cotationsProduct->estimate = 100;
    //         if (!$cotationsProductsTable->save($cotationsProduct)){
    //             $errors++;
    //         }
    //         //Salva o item
    //         $cotationsProductsItem = $cotationsProductsItemsTable->newEntity();
    //         $cotationsProductsItem->cotation_product_id = $cotationsProduct->id;
    //         $cotationsProductsItem->quantity = 1;
    //         $cotationsProductsItem->quote = 100;
    //         $cotationsProductsItem->product_id = 210; //ATENÇÃO - O PRODUTO PRECISA EXISTIR

    //         if (!$cotationsProductsItemsTable->save($cotationsProductsItem)){
    //             $errors++;
    //         }

    //     }


    //     $this->Flash->success(__("Cotações inseridas. Erros : $errors"));
    //     return $this->redirect(['action' => 'index']);
    // }

}

