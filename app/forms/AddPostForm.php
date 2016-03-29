<?php

use Nette\Application\UI;
use Nette\ComponentModel\IContainer;
use Nette\Application\UI\Form;

class AddPostForm extends UI\Form
{

    public function __construct(IContainer $parent = null, $name = null)
    {
        parent::__construct($parent, $name);

        $form->addText("title")->setRequired("Nevyplnili jste všechna políčka");

        $form->addText("teaser")->setRequired("Nevyplnili jste všechna políčka");

        $form->addCheckbox('active', 'Aktivní')->setAttribute('class', 'form-control');

        $form->addTextArea('body', 'Obsah:', 55, 5)->setRequired()->setAttribute('class', 'mceEditor');

        $form->addHidden('slug');

        $form->addSubmit("submit", "Přidat");
    }
}
