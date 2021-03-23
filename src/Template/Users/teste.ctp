<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\TableRegistry;

/**
 * Home Controller
 *
 *
 * @method \App\Model\Entity\Home[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HomeController extends AppController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['recoverPassword']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */

    public function recoverPassword()
    {
        // src\Template\Layout\adminlte_login.ctp
        $this->viewBuilder()->setLayout('adminlte_login');

        if ($this->request->is('post')) {
            $data = [
                'result' => 'failed',
            ];
            $email = $this->request->getData('email');

            $usersTable = TableRegistry::get('Users');
            $user = $usersTable->find()
                ->where(['email' => $email, 'active' => 1, 'role IN' => [0, 1]])
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
}
