<?php
namespace App\Mailer;

use Cake\Mailer\Email;
use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    private function common()
    {
        $this->setFrom('no-reply@ngproc.com.br', 'NGProc');
    }

    public function recoverPassword($user, $newPassword)
    {
        $this->common();
        $this->setSubject('NGProc - Recuperação de senha');
        $this->setViewVars(['user' => $user, 'newPassword' => $newPassword]);
        $this->setEmailFormat('text');
        $this->subject();
        $this->to($user->email, $user->name);
    }
}
use MailerAwareTrait;
