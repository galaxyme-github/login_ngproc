<?php
namespace App\Controller\Client;

use App\Controller\AppController;

/**
 * Evaluations Controller
 *
 * @property \App\Model\Table\EvaluationsTable $Evaluations
 *
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EvaluationsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $user = $this->request->getAttribute('identity');
        $evaluations = $this->Evaluations
        ->find('all', ['contain' => ['Parter', 'Cotations' => 'Purchases' ]])
        ->where(['client_id' => $user->id, 'owner' => 'client', 'Evaluations.value' => 0, 'Purchases.status' => 3])
        ->toList();
        echo json_encode($evaluations);
        exit;
    }

    /**
     * View method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $evaluation = $this->Evaluations->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set('evaluation', $evaluation);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $userid = $this->request->getAttribute('identity')->id;
            $value = $this->request->getData()['value'];
            $parter = $this->request->getData()['parter_id'];

            $evaluation = $this->Evaluations->find()->where(['client_id' => $userid, 'parter_id' => $parter])->first();
            if (empty($evaluation)) {
                $evaluation = $this->Evaluations->newEntity();
            }
            $evaluation->client_id =  $userid;
            $evaluation->parter_id =  $parter;
            $evaluation->owner = 'client';
            $evaluation->value = $value;

            if ($this->Evaluations->save($evaluation)) {
                echo 'ok';
            } else {
                echo 'error';
            }
            exit;

        }
        //$users = $this->Evaluations->Users->find('list', ['limit' => 200]);
        //$this->set(compact('evaluation', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $evaluation = $this->Evaluations->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $evaluation->value = $this->request->getData()['value'];

            if ($this->Evaluations->save($evaluation)) {
                echo 'ok';
            } else {
                echo 'error';
            }
        }
        exit;
    }

    /**
     * Delete method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $evaluation = $this->Evaluations->get($id);
        if ($this->Evaluations->delete($evaluation)) {
            $this->Flash->success(__('The evaluation has been deleted.'));
        } else {
            $this->Flash->error(__('The evaluation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
