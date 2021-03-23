<?php
namespace App\Controller\Client;

use App\Controller\AppController;
use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;

/**
 * Profile Controller
 *
 *
 * @method \App\Model\Entity\Profile[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfileController extends AppController
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

        $user = $this->request->getAttribute('identity');

        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->get($user->id, [
            'contain' => [],
        ]);

        $userEmail = $user->email;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $requestData = $this->request->getData();
            $changePassword = true;

            if (empty($requestData['birth_date'])) {
                unset($requestData['birth_date']);
            }

            if (empty($requestData['password'])) {
                unset($requestData['password']);
                $changePassword = false;
            }
            if (!empty($requestData['birth_date'])) {
                $requestData['birth_date'] = Chronos::createFromFormat('d/m/Y', $requestData['birth_date'])->format('Y-m-d');
            }
            $user = $usersTable->patchEntity($user, $requestData);

            if ($changePassword && $requestData['password'] === $requestData['confirm-password']) {
                $user->password = $user->hash($user->password);
            }

            //TRATAR: QUANDO A IMAGEM ATUAL FOR A DEFAULT DESABILITAR BTN REMOVER

            //Verificando se a imagem atual do perfil foi removida ou não
            $ImagemRemovida = $requestData['removedImageProfile'];
            if ($ImagemRemovida != 0) {
                //Código para apagar as imagens não usadas por perfil nenhum
                $removido = unlink(WWW_ROOT . '/uploads/profile/' . $user->img_profile);
                $user->img_profile = "";
            }
            //Adicionando a nova imagem de perfil do parceiro
            else if (isset($_FILES['nova_imagem'])) {
                $novaImagemPerfil = $_FILES['nova_imagem'];
                $novaImagemNome = $novaImagemPerfil['name'];
                $novaImagemExtensao = substr($novaImagemNome, strrpos($novaImagemNome, '.') + 1);
                if (in_array($novaImagemExtensao, ['png', 'jpg'])) {
                    $nomedoarquivo = uniqid() . '.' . $novaImagemExtensao;

                    if (@move_uploaded_file($novaImagemPerfil['tmp_name'], WWW_ROOT . '/uploads/profile/' . $nomedoarquivo)) {
                        $user->img_profile = $nomedoarquivo;
                    }
                }
            }


            if ($requestData['email'] != $userEmail && $usersTable->exists(['email' => $requestData['email']])) {
                $this->Flash->error(__('Já existe esse e-mail cadastrado no sistema'));
            } else {
                if ($usersTable->save($user)) {
                    $this->Flash->success(__('Usuário atualizado com sucesso.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('O usuário não pode ser atualizado. Por favor, tente novamente.'));
            }
        }
        // $imgProfileUser = $this->getImageProfile($user);
        // $this->set(compact('imgProfileUser'));
        $this->set(compact('user'));
    }

    /**
     * View method
     *
     * @param string|null $id Profile id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $profile = $this->Profile->get($id, [
            'contain' => [],
        ]);

        $this->set('profile', $profile);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $profile = $this->Profile->newEntity();
        if ($this->request->is('post')) {
            $profile = $this->Profile->patchEntity($profile, $this->request->getData());
            if ($this->Profile->save($profile)) {
                $this->Flash->success(__('The profile has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The profile could not be saved. Please, try again.'));
        }
        $this->set(compact('profile'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Profile id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usersTable = TableRegistry::get('Users');
        $client = $usersTable->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $requestData = $this->request->getData();
            $changePassword = true;

            if (empty($requestData['password'])) {
                unset($requestData['password']);
                $changePassword = false;
            }
            $client = $usersTable->patchEntity($client, $requestData);

            if ($changePassword && $requestData['password'] === $requestData['confirm-password']) {
                $client->password = $client->hash($client->password);
            }

            if ($usersTable->save($client)) {
                $this->Flash->success(__('Usuário atualizado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O usuário não pode ser atualizado. Por favor, tente novamente.'));
        }
        $this->set(compact('client'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Profile id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $profile = $this->Profile->get($id);
        if ($this->Profile->delete($profile)) {
            $this->Flash->success(__('The profile has been deleted.'));
        } else {
            $this->Flash->error(__('The profile could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    // public function getImageProfile($user){
    //     return $user->getProfileUrl();
    // }
}
