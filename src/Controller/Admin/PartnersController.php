<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;

/**
 * Partners Controller
 *
 *
 * @method \App\Model\Entity\Partner[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PartnersController extends AppController
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

        $partners = $usersTable
            ->find()
            ->where(['role' => 2])
            ->order(['created' => 'DESC']);

        $query = $this->request->getQueryParams();
        if (!empty($query['parceiro-search'])) {
            $partners->andWhere(['name LIKE' => "%{$query['parceiro-search']}%"]);
        }
        if (!empty($query['parceiro-active'])) {
            $partners->andWhere(['active ' => 1]);
        }

        $this->paginate = ['maxLimit' => 15];
        $partners = $this->paginate($partners);

        $this->set(compact('partners'));
    }

    /**
     * View method
     *
     * @param string|null $id Partner id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $partner = $this->Partners->get($id, [
            'contain' => [],
        ]);

        $this->set('partner', $partner);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $partner = $this->Partners->newEntity();
        if ($this->request->is('post')) {
            $partner = $this->Partners->patchEntity($partner, $this->request->getData());
            if ($this->Partners->save($partner)) {
                $this->Flash->success(__('Usuário atualizado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O usuário não pode ser atualizado. Por favor, tente novamente.'));
        }
        $this->set(compact('partner'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Partner id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usersTable = TableRegistry::get('Users');
        $partner = $usersTable->get($id, [
            'contain' => [],
        ]);
        $userEmail = $partner->email;
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
            $partner = $usersTable->patchEntity($partner, $requestData);

            if ($changePassword && $requestData['password'] === $requestData['confirm-password']) {
                $partner->password = $partner->hash($partner->password);
            }

            if ($requestData['email'] != $userEmail && $usersTable->exists(['email' => $requestData['email']])) {
                $this->Flash->error(__('Já existe esse e-mail cadastrado no sistema'));
            } else {
                if ($usersTable->save($partner)) {
                    $this->Flash->success(__('Usuário atualizado com sucesso.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('O usuário não pode ser atualizado. Por favor, tente novamente.'));
            }
        }
        $this->set(compact('partner', 'id'));
    }

    public function finance($id = null)
    {
        $usersTable = TableRegistry::get('Users');
        $partner = $usersTable->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $requestData = $this->request->getData();

            $partner = $usersTable->patchEntity($partner, $requestData);

            if ($usersTable->save($partner)) {
                $this->Flash->success(__('Usuário atualizado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O usuário não pode ser atualizado. Por favor, tente novamente.'));
        }
        $this->set(compact('partner', 'id'));
    }

    public function corporate($id = null)
    {
        $usersTable = TableRegistry::get('Users');
        $partner = $usersTable->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $requestData = $this->request->getData();

            $partner = $usersTable->patchEntity($partner, $requestData);

            if ($usersTable->save($partner)) {
                $this->Flash->success(__('Usuário atualizado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O usuário não pode ser atualizado. Por favor, tente novamente.'));
        }
        $this->set(compact('partner', 'id'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Partner id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function banir($id = null)
    {
        $usersTable = TableRegistry::get('Users');
        $partner = $usersTable->get($id);

        $partner->active = 0;

        if ($usersTable->save($partner)) {
            $this->Flash->success(__('Usuário banido com sucesso.'));
        } else {
            $this->Flash->error(__('O usuário não pode ser banido. Por favor, tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function desable(){

        $response = [
            'result' => 'failed',
        ];

        $usersTable = TableRegistry::get('Users');

        if ($this->request->is('post')) {
            $requestData = $this->request->getData();

            $partner = $usersTable->get($requestData['id']);
            $partner->optout_email = 1;
            $partner->active = 0;

            if ($usersTable->save($partner)) {
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

            $partner = $usersTable->get($requestData['id']);

            $partner->active = 1;

            if ($usersTable->save($partner)) {
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
