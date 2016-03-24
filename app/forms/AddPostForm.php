<?php

use Nette\Application\UI,
    Nette\ComponentModel\IContainer;
use Nette\Application\UI\Form;
class AddPostForm extends UI\Form {

    public function __construct(IContainer $parent = NULL, $name = NULL) {
        parent::__construct($parent, $name);

	$form->addText("title")->setRequired("Nevyplnili jste všechna políčka");

	$form->addText("teaser")->setRequired("Nevyplnili jste všechna políčka");

    $form->addCheckbox('active', 'Aktivní')->setAttribute('class', 'form-control'); 

    $form->addTextArea('body', 'Obsah:',55, 5)->setRequired()->setAttribute('class', 'mceEditor');

    $form->addHidden('slug');

	$form->addSubmit("submit", "Přidat");
    }

}
