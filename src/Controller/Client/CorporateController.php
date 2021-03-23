<?php
namespace App\Controller\Client;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * Corporate Controller */

/* @method \App\Model\Entity\Corporate[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])*/

class CorporateController extends AppController
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

        $cotations = $cotationsTable->find()->contain(['CotationServices', 'CotationProducts' => 'CotationProductItems'])->where(['user_id' => $user->id, 'main_cotation_id IS NULL']);
        $query = $this->request->getQueryParams();
        if (!empty($query['cotacao-search'])) {
            $cotations->andWhere(['title LIKE' => "%{$query['cotacao-search']}%"]);
        }

        $this->paginate = ['maxLimit' => 15];
        $cotations = $this->paginate($cotations);
        $this->set(compact('cotations'));
    }

    public function add()
    {
       
        $projectsTable = TableRegistry::get('Projects');
        $kpiTable = TableRegistry::get('Kpi');

        if ($this->request->is('post')) {
            
            $request = $this->request->getData();
            $project = $projectsTable->newEntity();
            $project->hours = $request['hours'];
            $project->description = $request['description'];
            $project->distribution = $request['distribution'];
            if ( $projectsTable->save($project) ) {
                foreach ($request['name'] as $key => $value) {
                    $kpi = $kpiTable->newEntity();
                    $kpi->name = $request['name'][$key];
                    $kpi->meta_type = $request['meta_type'][$key];
                    $kpi->meta_value = $request['meta_value'][$key];
                    $kpi->project_id = $project->id;
                    $kpiTable->save($kpi);
                    
                }
            }
            $this->Flash->success(__('Novo projeto publicado.'));
            return $this->redirect(['action' => 'add']);
        }
    }

    public function parceiros(){
        
    }

}
?>