<?php
namespace App\Controller;
use Cake\Mailer\MailerAwareTrait;
use App\Controller\AppController;

/**
 * SendGrids Controller
 *
 * @property \App\Model\Table\SendGridsTable $SendGrids
 *
 * @method \App\Model\Entity\SendGrid[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SendGridsController extends AppController
{
    use MailerAwareTrait;
    public function beforeFilter(\Cake\Event\Event $event)
    {
        $this->Authentication->allowUnauthenticated(['index']);
    }
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }
    /**
     * Send method
     *
     */
    public function index()
    {
        // Inicie uma nova consulta.
    $sendGrids = $this->SendGrids
        ->find()
        ->where(['send'=> 0])
        ->order(['created' => 'ASC']);

   foreach ($sendGrids as $key => $sendGrid) {
      $content = http_build_query(
            array(
            'type' => 1,
            'category' => $sendGrid->category,
            )
        );

        $context = stream_context_create(
            array('http' =>
                array(
                    'method' => 'POST',
                    'content' => $content,
                )
            )
        );

        file_get_contents('https://ngproc.com.br/sendgrid/index.php', null, $context);

        //Assinalar o envio apos o termino da requisição
        $sendGridFind = $this->SendGrids->get( $sendGrid->id );
        $sendGridFind->send = 1;
        $this->SendGrids->save($sendGridFind);

    }

    exit;

    }
}
