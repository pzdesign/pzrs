<?php

namespace AdminModule;

use Nette;
use Nette\Utils\Paginator;
use App\Model\UserManager;
use App\Model\RulesForms;
/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $pm;
	protected $rm;
	protected $tm;
	protected $em;

    public function inject(\App\Model\PostsManager $pm, \App\Model\ResultsManager $rm, \App\Model\TeamsManager $tm, \App\Model\EnemyManager $em){
	$this->pm = $pm;
	$this->rm = $rm;
	$this->tm = $tm;
	$this->em = $em;
    }

	public function beforeRender()
	{
        $this->setLayout('layout');		
	}

    public function handleLogOut() {
        $this->user->logout();
        $this->flashMessage('Byl jste odhlášen', 'info');
        $this->redirect(':Front:Homepage:default');

    }

}
