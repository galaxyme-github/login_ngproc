<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Teste Controller
 *
 *
 * @method \App\Model\Entity\Teste[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TesteController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        $this->Authentication->allowUnauthenticated(
            ['testeCotation', 'testeInsertCotation']);
    }

    public function testeCotation()
    {

        $cotationsTable = TableRegistry::get('Cotations');
        $cotation = $cotationsTable->get(2, ['contain' => ['CotationServices', 'CotationProducts' => 'CotationProductItems']]);

        dump($cotation);
    }

    public function testeInsertCotation()
    {
        $cotationsTable = TableRegistry::get('Cotations');
        $association = ['CotationServices', 'CotationProducts' => 'CotationProductItems'];

        $fields = [
            'title' => 'Teste Thiago',
            'cotation_type' => 'Produtos',
            'provider_qtd' => '1',
            'objective' => 'Teste',
            'deadline_date' => '12/12/2019',
            'status' => 'Pendente',
            'coverage' => 'Nacional',
            'cotation_service' => [
                'category' => 'catasd',
                'collection_type' => 'asd',
                'description' => '123',
                'estimate' => '0.80',
                'expectation_start' => 'bababa',
                'service_time' => 'bababa',
            ],
        ];

        $cotation = $cotationsTable->newEntity(null, ['associated' => $association]);
        $cotation = $cotationsTable->patchEntity($cotation, $fields, ['associated' => $association]);

        if ($cotationsTable->save($cotation)) {
            dump('salvou');
        }
        //dump($cotation);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $teste = $this->paginate($this->Teste);

        $this->set(compact('teste'));
    }

    /**
     * View method
     *
     * @param string|null $id Teste id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $teste = $this->Teste->get($id, [
            'contain' => [],
        ]);

        $this->set('teste', $teste);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $teste = $this->Teste->newEntity();
        if ($this->request->is('post')) {
            $teste = $this->Teste->patchEntity($teste, $this->request->getData());
            if ($this->Teste->save($teste)) {
                $this->Flash->success(__('The teste has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The teste could not be saved. Please, try again.'));
        }
        $this->set(compact('teste'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Teste id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $teste = $this->Teste->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $teste = $this->Teste->patchEntity($teste, $this->request->getData());
            if ($this->Teste->save($teste)) {
                $this->Flash->success(__('The teste has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The teste could not be saved. Please, try again.'));
        }
        $this->set(compact('teste'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Teste id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $teste = $this->Teste->get($id);
        if ($this->Teste->delete($teste)) {
            $this->Flash->success(__('The teste has been deleted.'));
        } else {
            $this->Flash->error(__('The teste could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
