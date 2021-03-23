<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;

/**
 * Clients Controller
 *
 *
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('dashboard');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $usersTable = TableRegistry::get('Users');

        $clients = $usersTable
            ->find()
            ->where(['role' => 1])
            ->order(['created' => 'DESC']);

        $query = $this->request->getQueryParams();
        if (!empty($query['cliente-search'])) {
            $clients->andWhere(['name LIKE' => "%{$query['cliente-search']}%"]);
        }

        $this->paginate = ['maxLimit' => 15];
        $clients = $this->paginate($clients);

        $this->set(compact('clients'));
    }

    /**
     * View method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $client = $this->Clients->get($id, [
            'contain' => [],
        ]);

        $this->set('client', $client);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $client = $this->Clients->newEntity();
        if ($this->request->is('post')) {
            $client = $this->Clients->patchEntity($client, $this->request->getData());
            if ($this->Clients->save($client)) {
                $this->Flash->success(__('Usuário atualizado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O usuário não pode ser atualizado. Por favor, tente novamente.'));
        }
        $this->set(compact('client'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usersTable = TableRegistry::get('Users');
        $client = $usersTable->get($id, [
            'contain' => [],
        ]);
        $userEmail = $client->email;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $requestData = $this->request->getData();
            $changePassword = true;

            if (empty($requestData['password'])) {
                unset($requestData['password']);
                $changePassword = false;
            }
            if (!empty($requestData['birth_date'])) {
                $requestData['birth_date'] = Chronos::createFromFormat('d/m/Y', $requestData['birth_date'])->format('Y-m-d');
            }

            $client = $usersTable->patchEntity($client, $requestData);

            if ($changePassword && $requestData['password'] === $requestData['confirm-password']) {
                $client->password = $client->hash($client->password);
            }

            if ($requestData['email'] != $userEmail && $usersTable->exists(['email' => $requestData['email']])) {
                $this->Flash->error(__('Já existe esse e-mail cadastrado no sistema'));
            } else {
                if ($usersTable->save($client)) {
                    $this->Flash->success(__('Usuário atualizado com sucesso.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('O usuário não pode ser atualizado. Por favor, tente novamente.'));
            }
        }
        $this->set(compact('client', 'id'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function banir($id = null)
    {
        $usersTable = TableRegistry::get('Users');
        $client = $usersTable->get($id);

        $client->active = 0;

        if ($usersTable->save($client)) {
            $this->Flash->success(__('Usuário suspenso com sucesso.'));
        } else {
            $this->Flash->error(__('O usuário não pode ser suspenso. Por favor, tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function delete(){
        $response = [
            'result' => 'failed',
        ];

        $usersTable = TableRegistry::get('Users');
        $cotationsTable = TableRegistry::get('Cotations');
        $cotationAttachmentsTable = TableRegistry::get('CotationAttachments');
        $cotationServicesTable = TableRegistry::get('CotationServices');
        $cotationProductsTable = TableRegistry::get('CotationProducts');
        $cotationProductItemsTable = TableRegistry::get('CotationProductItems');

        if ($this->request->is('post')) {
            $requestData = $this->request->getData();

            //Verificando se existe cotação deste cliente
            $result = $cotationsTable->find()->where(['user_id' => $requestData['id']])->all()->toArray();
            if( empty($result) ){
                $user = $usersTable->get($requestData['id']);
                if( $usersTable->delete($user) ){
                    $response['result'] = 'success';
                }else{
                    $response['result'] = 'Falha ao tentar excluir usuário.';
                }
            }else{
                foreach ($result as $k => $cotation) {
                    $hasSents = $cotationsTable->find()->where(['main_cotation_id' => $cotation->id])->all()->toArray();
                    if( !empty($hasSents) ){
                        $response['result'] = 'Usuário não pode ser excluído, pois possui cotações que receberam propostas de parceiros.';
                        break;
                    }
                }

                if( empty($hasSents) ){
                    foreach ($result as $k => $cotation) {
                        $c = $cotationsTable->get($cotation->id, [
                            'contain' => [
                                'CotationServices',
                                'CotationProducts' => 'CotationProductItems',
                                'CotationAttachments'
                            ]
                        ]);

                        if( !empty($c['cotation_attachments']) ){
                            $nivel1 = $cotationAttachmentsTable->get($c['cotation_attachments']['id']);
                            $nivel1 = $cotationAttachmentsTable->delete($nivel1);
                        }

                        if( !empty($c['cotation_product']) ){

                            if( !empty($c['cotation_product']['cotation_product_items']) ){

                                for ($i=0; $i < count($c['cotation_product']['cotation_product_items']); $i++) {
                                    $nivel3 = $cotationProductItemsTable->get($c['cotation_product']['cotation_product_items'][$i]['id']);
                                    $nivel3 = $cotationProductItemsTable->delete($nivel3);
                                }

                                $nivel2 = $cotationProductsTable->get($c['cotation_product']['id']);
                                $nivel2 = $cotationProductsTable->delete($nivel2);
                            }

                        }

                        if( !empty($c['cotation_service']) ){
                            $nivel2 = $cotationServicesTable->get($c['cotation_service']['id']);
                            $nivel2 = $cotationServicesTable->delete($nivel2);
                        }


                        $ex = $cotationsTable->get($c['id']);

                        if( $cotationsTable->delete($ex) ){
                            $response['result'] = 'success';
                        }else{
                            $response['result'] = 'Falha ao tentar excluir usuário.';
                        }

                    }

                    if( $response['result'] == 'success'){
                        $user = $usersTable->get($requestData['id']);
                        if( $usersTable->delete($user) ){
                            $response['result'] = 'success';
                        }else{
                            $response['result'] = 'Falha ao tentar excluir usuário.';
                        }
                    }

                }
            }
        }

        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }

    public function desable(){

        $response = [
            'result' => 'failed',
        ];

        $usersTable = TableRegistry::get('Users');

        if ($this->request->is('post')) {
            $requestData = $this->request->getData();

            $client = $usersTable->get($requestData['id']);

            $client->active = 0;

            if ($usersTable->save($client)) {
                $response['result'] = 'success';
            } else {
                $response['result'] = 'O usuário não pôde ser suspenso. Por favor, tente novamente.';
            }
        }

        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }

    public function enable(){

        $response = [
            'result' => 'failed',
        ];

        $usersTable = TableRegistry::get('Users');

        if ($this->request->is('post')) {
            $requestData = $this->request->getData();

            $client = $usersTable->get($requestData['id']);

            $client->active = 1;

            if ($usersTable->save($client)) {
                $response['result'] = 'success';
            } else {
                $response['result'] = 'O usuário não pôde ser reativado. Por favor, tente novamente.';
            }
        }

        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }
}
