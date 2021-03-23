<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;

/**
 * Providers Controller
 *
 *
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProvidersController extends AppController
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
        $providersTable = TableRegistry::get('Providers');

        $providers = $providersTable
            ->find()
            ->order(['created' => 'DESC'])
            ->all()
            ->toArray();

        // $query = $this->request->getQueryParams();
        // if (!empty($query['provider-search'])) {
        //     $providers->andWhere(['name LIKE' => "%{$query['provider-search']}%"]);
        // }

        // $this->paginate = ['maxLimit' => 15];
        // $providers = $this->paginate($providers);

        $this->set(compact('providers'));
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
        $providersTable = TableRegistry::get('Providers');
        $provider = $providersTable->get($id, [
            'contain' => [],
        ]);

        $this->set('provider', $provider);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        
        $provider = $this->Providers->newEntity();
        if ($this->request->is('post')) {
            //Verifica se ja existe o CNPJ CADASTRADO
            $providerFind = $this->Providers->find()->where(['cnpj' =>$this->request->getData()['cnpj']]);
            if ($providerFind->count() != 0){
                $this->Flash->error(__('Fornecedor já cadastrado.'));

                return $this->redirect(['action' => 'index']);
            }
            $provider = $this->Providers->patchEntity($provider, $this->request->getData());
            if ($this->Providers->save($provider)) {
                $this->Flash->success(__('Fornecedor salvo.'));

                return $this->redirect(['action' => 'edit', $provider->id]);
            }
            $this->Flash->error(__('Não foi possivel cadastrar fornecedor . Por favor, tente novamente.'));
        }
        $this->set(compact('provider'));
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
        $provider = $this->Providers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $provider = $this->Providers->patchEntity($provider, $this->request->getData());
            if ($this->Providers->save($provider)) {
                $this->Flash->success(__('Dados salvos.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Erro ao salvar. Por favor, tente novamente.'));
        }
        $this->set(compact('provider'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    // public function delete($id = null)
    // {
        
    //     $cotationProvidersTable = TableRegistry::get('CotationProviders');
        
    //     $cotations = $cotationProvidersTable->find()->where(['provider_id' => $id]);
        
    //     if ($cotations->count() != 0 ){
    //         $this->Flash->error(__('Exite(m) '. $cotations->count() . ' cotações associadas a este fornecedor!'));
    //         return $this->redirect(['action' => 'index']);
    //     }        

    //     $this->request->allowMethod(['get', 'delete']);
    //     $provider = $this->Providers->get($id);
        
    //     if ($this->Providers->delete($provider)) {
    //         $this->Flash->success(__('Fornecedor excluído.'));
    //     } else {
    //         $this->Flash->error(__('Falha ao excluir fornecedor. Tente novamente.'));
    //     }
    //     return $this->redirect(['action' => 'index']);
      
    // }
    
    public function findProviderByCnpj()
    {
        $response = [
            'result' => 'failed'
        ];

        $cnpj = $this->request->getQuery('cnpj', null);
        if ($cnpj !== null) {
            $providersTable = TableRegistry::get('Providers');
            $provider = $providersTable->find()->where(['cnpj' => $cnpj])->first();

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

    public function delete(){

        $response = [
            'result' => 'failed',
        ];

        $providersTable = TableRegistry::get('Providers');
        $cotationProvidersTable = TableRegistry::get('CotationProviders');      

        if ($this->request->is('post')) {
            $requestData = $this->request->getData();

            $result = $cotationProvidersTable->find()->where(['provider_id' => $requestData['id']])->all()->toArray();

            if( empty($result) ){
                $p = $providersTable->get($requestData['id']);
                if( $providersTable->delete($p) ){
                    $response['result'] = 'success';
                }else{
                    $response['result'] = 'Não foi possível excluir fornecedor';
                }
            }else{
                $response['result'] = 'Este fornecedor não pode ser excluído. Há parceiros o utilizando.';
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

        $providersTable = TableRegistry::get('Providers');        

        if ($this->request->is('post')) {
            $requestData = $this->request->getData();

            $provider = $providersTable->get($requestData['id']);

            $provider->active = 0;

            if ($providersTable->save($provider)) {
                $response['result'] = 'success';
            } else {
                $response['result'] = 'O usuário não pode ser banido. Por favor, tente novamente.';
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

        $providersTable = TableRegistry::get('Providers');        

        if ($this->request->is('post')) {
            $requestData = $this->request->getData();

            $provider = $providersTable->get($requestData['id']);

            $provider->active = 1;

            if ($providersTable->save($provider)) {
                $response['result'] = 'success';
            } else {
                $response['result'] = 'O usuário não pode ser reativado. Por favor, tente novamente.';
            }
        }

        $this->set(compact('response'));
        $this->set('_serialize', 'response');
        $this->RequestHandler->renderAs($this, 'json');
    }
}
