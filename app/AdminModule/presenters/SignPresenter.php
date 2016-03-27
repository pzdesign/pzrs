<?php

namespace AdminModule;

use Nette,
    Nette\Forms\Controls,
    Nette\Application\UI\Form;
    
use App\Model\UserManager;


class SignPresenter extends BasePresenter
{

    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function actionIn() {
        $this->template->passUpdated = false;
            $this->redrawControl('flashMessages');  
    }


    public function actionCreate()
    {
        
        $register = new UserManager($this->database);
        $register->add('admin','123456');
        $register->add('test','test');
        $this->flashMessage('Vytvořeno.', 'alert-success');
        
        $this->redirect('Sign:in');
    }

    /**
     * Sign-in form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentSignInForm($name) {

        $form = new \SignInForm($this, $name);
        $form->onSuccess[] = array($this, 'signInFormSucceeded');
        return $form;
    }
    public function signInFormSucceeded($form,$values)
    {
        //$register = new UserManager($this->database);
       // $register->add('test','test');       
        if ($values->remember) {
            $this->getUser()->setExpiration('14 days', FALSE);
        } else {
            $this->getUser()->setExpiration('20 minutes', TRUE);
        }
        $values = $form->values;
        try {
            $this->getUser()->login($values->username, $values->password);
            $this->flashMessage('Přihlášení bylo úspěšné.', 'success');
            $this->redrawControl('flashMessages');  
            $this->redirect('Admin:');
        } catch (Nette\Security\AuthenticationException $e) {
            $this->redrawControl('flashMessages');  
            $this->flashMessage('Nesprávné přihlašovací jméno nebo heslo.', 'danger');
        }
    }

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.', 'success');
        $this->redrawControl('flashMessages');          
        $this->redirect(':Front:Homepage:');
    }
}
