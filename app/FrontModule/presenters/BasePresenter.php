<?php

namespace FrontModule;

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

    public function inject(\App\Model\PostsManager $pm, \App\Model\ResultsManager $rm)
    {
        $this->pm = $pm;
        $this->rm = $rm;
    }

  /*  
    public function injectBase(Market\IConomyRepository $icr, Market\CartRepository $cp, Market\SettingsRepository $sr, Market\ItemsRepository $itr){
    $this->icr = $icr;
    $this->cp = $cp;
    $this->sr = $sr;
    $this->itr = $itr;
    }
*/
    //private $paginator;

    public function beforeRender()
    {
        if ($this->isAjax()) {
            $this->redrawControl("flashMessages");
        }
    }
}
