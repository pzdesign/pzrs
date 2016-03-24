<?php

namespace App\Presenters;

use Nette;
use Nette\Utils\Paginator;
use App\Model\UserManager;
use App\Model\PostsManager;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $pm;
	private $paginator;

    public function inject(PostsManager $pm){
	$this->pm = $pm;
    }


	public function beforeRender()
	{
	if($this->isAjax()){
	    $this->redrawControl("flashMessages");
	}
	}

}
