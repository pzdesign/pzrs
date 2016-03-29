<?php

use Nette\Application\UI;
use Nette\ComponentModel\IContainer;
use Nette\Application\UI\Form;

class SignInForm extends UI\Form
{

    public function __construct(IContainer $parent = null, $name = null)
    {
        parent::__construct($parent, $name);

        $this->addText('username', 'Jméno')->addRule($this::MAX_LENGTH, 'Login is way too long', 50)->setRequired('Please enter your Login.');
        $this->addPassword('password', 'Heslo')->addRule($this::MAX_LENGTH, 'Password is way too long', 50)->setRequired('Please enter your Password.');
        $this->addCheckbox('remember', 'Zůstat přihlášen');
        $this->addSubmit('send', 'Přihlásit se');
    }
}
