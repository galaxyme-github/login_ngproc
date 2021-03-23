<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\TableRegistry;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    use MailerAwareTrait;
    public function beforeFilter(\Cake\Event\Event $event)
    {
        $this->Authentication->allowUnauthenticated(['index', 'login', 'register', 'layout4', 'recoverPassword', 'checkCpf', 'checkEmail']);
    }

    public function index(){
        $user = $this->request->getAttribute('identity');

        if ($user) {
            switch ($user->role) {
                case 0:
                    // return $this->redirect(['prefix' => 'admin', 'controller' => 'Home', 'action' => 'index']); //Mudando a rota até a segunda ordem
                    return $this->redirect(['prefix' => 'admin', 'controller' => 'Clients', 'action' => 'index']);
                case 1:

                    return $this->redirect(['prefix' => 'client', 'controller' => 'Quotation', 'action' => 'index']);
                case 2:
                    return $this->redirect(['prefix' => 'partner', 'controller' => 'Quotation', 'action' => 'index']);
            }
        }

        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    public function login(){
        $this->viewBuilder()->disableAutoLayout();

        if ($this->request->is('post')) {
            $result = $this->Authentication->getResult();

            if ($result->isValid()) {
                $user = $this->request->getAttribute('identity');
                if ($user->active == 0) {
                    $this->Flash->error(__('Usuário está suspenso, contate o administrador do sistema.'));
                    return $this->redirect(
                        ['action' => 'login']
                    );
                }
                $this->request->getAttribute('identity');
                if ($this->request->getCookie('direct_link') == '1') {
                    setcookie( 'direct_link', '0', time()+60*10, "/", "ngproc.com" );
                    return $this->redirect('client/quotation/add_new');
                }
                else
                    return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Usuário ou senha incorreto, por favor tente novamente.'));
                return $this->redirect(
                    ['action' => 'login']
                );
            }
        } else {
            $user = $this->request->getAttribute('identity');
            if ($user) {
                $this->Authentication->logout();
            }
        }
    }

    public function logout(){
        $this->Authentication->logout();

        return $this->redirect(
            ['action' => 'login']
        );
    }

    public function register(){
        $this->viewBuilder()->disableAutoLayout();

        if ($this->request->is('post')) {

            $requestData = $this->request->getData();
            $requestData['name'] = strtoupper($requestData['name']);
            if ($this->Users->exists(['email' => $requestData['email']])) {
                $data['message'] = 'Já existe um usuário com este e-mail.';
                $data['reason'] = 'email';
            } else {

                $requestData['terms_of_use'] = 1;

                $user = $this->Users->patchEntity($this->Users->newEntity(null), $requestData);

                $user->password = $user->hash($user->password);
                if ($user->role == 0) {
                    $user->role = 1;
                }

                $user->active = 1;
                $user->first_login = 0;
                $user->confirmed_email = 0;

                date_default_timezone_set('America/Sao_Paulo');
                $date = date('Y-m-d H:i');
                $user->created = $date;

                if ($this->Users->save($user)) {
                    $data['result'] = 'success';
                    $data['message'] = 'Conta criada com sucesso.';
                    $this->Flash->success(__('Usuário cadastrado com sucesso'));
                    // $this->request->getAttribute('identity');
                    // return $this->redirect(['action' => 'index']);
                    return $this->redirect(['action' => 'login']);
                } else {
                    $data['message'] = 'Ocorreram erros ao tentar criar conta.';
                    $data['errors'] = $user->errors();
                    $this->setResponse($this->response->withStatus(400));
                }
            }
        }
    }

    public function checkCpf($tpUser = 1,$inscricao = null, $complemento =''){

        $inscricao .= $complemento != '' ? '/'.$complemento : '';
         $data = [
            'exists' => $inscricao !== null && $this->Users->exists([ 'role' => $tpUser, 'OR' => ['doc_cnpj' => $inscricao, 'doc_cpf' => $inscricao] ]),
        ];

        $this->RequestHandler->renderAs($this, 'json');
        $this->set(compact('data'));
        $this->set('_serialize', 'data');
    }

    public function checkEmail($email = null){
        $data = [
            'exists' => $email !== null && $this->Users->exists(['email' => $email]),
        ];

        $this->RequestHandler->renderAs($this, 'json');
        $this->set(compact('data'));
        $this->set('_serialize', 'data');
    }

    public function recoverPassword(){
        $this->viewBuilder()->disableAutoLayout();

        if ($this->request->is('post')) {
            $data = [
                'result' => 'failed',
            ];
            $email = $this->request->getData('email');

            $usersTable = TableRegistry::get('Users');
            $user = $usersTable->find()
                ->where(['email' => $email, 'active' => 1])
                ->first();

            if ($user) {
                //recuperar senha aqui
                try {
                    $newPassword = $user->generateRandomPassword(12);
                    $user->password = $user->hash($newPassword);
                    if ($usersTable->save($user)) {
                        $data['result'] = 'success';
                        $this->getMailer('User')->send('recoverPassword', [$user, $newPassword]);
                    }
                } catch (\Exception $e) {
                    $data['result'] = 'failed';
                    $data['error'] = $e->getMessage();
                }
            } else {
                $data['result'] = 'notfound';
            }

            $this->set(compact('data'));
            $this->set('_serialize', 'data');
            $this->RequestHandler->renderAs($this, 'json');
        }
    }

    public function tempdashboard(){
        $this->viewBuilder()->setLayout('dashboard');
    }

    public function layout1()
    {
        $this->viewBuilder()->setLayout('dashboard');
    }
    public function layout2()
    {
        $this->viewBuilder()->setLayout('dashboard');
    }
    public function layout3()
    {
        $this->viewBuilder()->setLayout('dashboard');
    }
    public function layout4()
    {
        $this->viewBuilder()->setLayout('dashboard');
    }
    public function layout5()
    {
        $this->viewBuilder()->setLayout('dashboard');
    }
}
