<?php
namespace App\Controller\Partner;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;
/**
 * Quotation Controller
 *
 *
 * @method \App\Model\Entity\Quotation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RelatoriosController extends AppController{
    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('dashboard');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index(){
        $user = $this->request->getAttribute('identity');

        $usersTable = TableRegistry::get('Users');
        $purchaseTable = TableRegistry::get('Purchases');
        $cotationsTable = TableRegistry::get('Cotations');

        $purchasesPagas = $purchaseTable->find()->where(['status' => 3])->order(['cotation_id' => 'DESC'])->all()->toArray();

        $cotationParaComissao = [];
        foreach ($purchasesPagas as $k => $pp) {
            //Verificando se alguma das cotações pagas pelo cliente foi deste parceiro
            $cotation = $cotationsTable->get($pp->cotation_id);
            if($cotation->user_id == $user->id){
                $cotation = $cotationsTable->find()->contain(['Users','Purchases', 'CotationServices', 'CotationProducts' => 'CotationProductItems.Products', 'CotationProviders' => 'Providers'])
                ->leftJoinWith('CotationServices')
                ->leftJoinWith('Users')
                ->leftJoinWith('CotationProducts.CotationProductItems.Products')
                ->select()
                ->distinct(['Cotations.id'])
                ->where(["Cotations.id" => $pp->cotation_id])
                ->first();
                $cotationParaComissao[$k] = array(
                    'purchase' => $pp,
                    'cotation' => $cotation
                );
            }
        }

        // $cotations = $cotationsTable->find()->contain(['Users','Purchases', 'CotationServices', 'CotationProducts' => 'CotationProductItems.Products', 'CotationProviders' => 'Providers'])
        // ->leftJoinWith('CotationServices')
        // ->leftJoinWith('Users')
        // ->leftJoinWith('CotationProducts.CotationProductItems.Products')
        // ->select()
        // ->distinct(['Cotations.id'])
        // ->where(["Cotations.id" => $cotationPagas->id]);
        $this->set(compact('cotationParaComissao'));
    }
}
