<?php
namespace App\Controller\Partner;

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
        
    }

    public function add() 
    {
      


    }

}
?>