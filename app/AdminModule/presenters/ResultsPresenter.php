<?php

namespace AdminModule;

use Nette,
Nette\Application\UI,
Nette\Forms\Controls,
Nette\Application\UI\Form,
Nette\Utils\Image,
Nette\Utils\Strings,
Nette\Utils\Finder;


class ResultsPresenter extends BasePresenter
{
	private $paginator;
	private $connection;
	private $posts = array();
	private $cleanCache;
	private $teams = array();
	public function startup() {
		parent::startup();
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}
	}


	public function actionDefault($page = 1) {

		$paginator = new Nette\Utils\Paginator;
		$paginator->setItemCount($this->rm->getPostsCount($this->getView()));
		$paginator->setItemsPerPage(10);
		$paginator->setPage($page);
		$length = $paginator->getLength();

		if($page < 1 || $page > $length) {
			//$this->flashMessage("Stránka musí být mezi 1 a $length.");
			$paginator->setPage(1);
		}
		$this->paginator = $paginator;

		$this->template->paginator = $this->paginator;

		$this->template->teams = $this->tm->findAll();

		$this->template->absImagePath = $this->context->parameters['wwwDir'];  

		$allPosts = $this->rm->getPostsLimited($this->paginator->getLength(), $this->paginator->getOffset())->order('id DESC');
		$this->template->posts = $allPosts; 
		//$this->redrawControl('addNewResult');
	}

	public function renderDefault()
	{      
		$this->template->paginator = $this->paginator;
		$allPosts = $this->rm->getPostsLimited($this->paginator->getLength(), $this->paginator->getOffset())->order('id DESC');
    	$this->template->posts = $allPosts; 
	}	

	protected function createComponentAddNewResultForm(){

		$teamsA = $this->tm->findAll();
		foreach($teamsA as $key => $teamA) {
		$teamsAArray[] = $teamA->teamA;
		}

		$teamsB = $this->em->findAll();
		foreach($teamsB as $key => $teamB) {
		$teamsBArray[] = $teamB->teamA;
		}


		$form = new Form;

		$form->addSelect('teamA', 'Team:')->setItems($teamsAArray, FALSE);
		$form->addSelect('teamB', 'Enemy:')->setItems($teamsBArray, FALSE);

		$form->addSubmit('submit', 'SAVE');

		$form->addSubmit('cancel', 'CLOSE')
		->onClick[] = [$this, 'formCancelled'];

		$form->onValidate[] = $this->addNewResultFormCheck;
		$form->onSuccess[] = $this->addNewResultFormSubmit;
		return $form;
	}


	public function addNewResultFormCheck($form)
	{
		if(!$this->isAjax()){
			$this->redirect('Admin:default');
		}

	    $values = $form->getValues();
	    if (strlen($values->teamA) < 1) { // validační podmínka    	
	        $form['teamA']->addError('Title must have atleast 3 char.');
	    }

	    if (strlen($values->teamB) < 3) { // validační podmínka    	
	        $form['teamB']->addError('Title must have atleast 3 char. ');	      
	    }

		$this->redrawControl('addNewResultSnippet');
		$this->redrawControl('flashMessages');

 
	}

	public function addNewResultFormSubmit($form){
		if(!$this->isAjax()){
			$this->redirect(':Admin:Results:');
		}	
	    $values = $form->getValues();

		$created_at = date("Y-m-d H:i:s");	

		$teamALogo = $this->tm->findByName($values->teamA)->fetch();
		$teamBLogo = $this->em->findByName($values->teamB)->fetch();

		$query = $this->rm->addItem($values->teamA, $teamALogo->teamALogo, $values->teamB, $teamBLogo->teamALogo, $created_at);
		if($query) {
    	$form->setValues(array(), TRUE); 
	
		$this->payload->resetForm = true; 

	  					  		
    	$this->redrawControl('addNewResultSnippet');			  		
    	$this->redrawControl('postsList');	
		$this->redrawControl('flashMessages');
	   	$this->paginator->setItemCount($this->rm->getPostsCount());	

		} else {
			$this->flashMessage("ERROR", "danger");
		}
	}

	protected function createComponentEditPostForm(){
		$teamsA = $this->tm->findAll();
		foreach($teamsA as $key => $teamA) {
		$teamsAArray[] = $teamA->teamA;
		}

		$teamsB = $this->em->findAll();
		foreach($teamsB as $key => $teamB) {
		$teamsBArray[] = $teamB->teamA;
		}

		$form = new Form;

		$form->addSelect('teamA', 'Team:')->setItems($teamsAArray, FALSE);
		$form->addSelect('teamB', 'Enemy:')->setItems($teamsBArray, FALSE);

		$form->addHidden('edited_at');

		$form->addSubmit("submit", "Uložit změny");

		$form->addSubmit('cancel', 'Zavřít')
		->setValidationScope([])
		->onClick[] = [$this, 'formCancelled'];

		$form->onSuccess[] = $this->editPostFormSubmitted;

		return $form;
	}




	public function editPostFormSubmitted(UI\Form $form, $values){
		$id = $this->getParameter('id');

		$values = $form->getValues();

		$values->edited_at = date("Y-m-d H:i:s");

		$teamALogo = $this->tm->findByName($values->teamA)->fetch();		
		$teamBLogo = $this->em->findByName($values->teamB)->fetch();


		$this->rm->editPost($id,$values->teamA, $teamALogo->teamALogo, $values->teamB, $teamBLogo->teamALogo, $values->edited_at);

		$this->flashMessage("Match byl upraven", "success");

		if(!$this->isAjax()){
			$this->redirect(':Admin:Results:');
		}

		$this->redrawControl('flashMessages');

	}



	public function actionEditPost($id)
	{
		$this->template->absImagePath = $this->context->parameters['wwwDir']; 
		$post = $this->rm->getById($id)->fetch();
	    if ($post->canBeEdited == 0) {
	    	$this->error('Tento příspěvek neleze editovat');	    	
	   		 }
		if (!$post) {
			$this->error('Příspěvek nebyl nalezen');
		}
		$this->template->post = $post;

		$this['editPostForm']->setDefaults($post->toArray());
	}

	public function handleActive($postId) {
		if(!is_numeric($postId)){
			$this->flashMessage("Nehrabej se, prosím, v URL.", "danger");
			return;
		}
		if(!$this->isAjax()) {
			$this->redirect("this");
		}

		$this->rm->setActive($postId, 1);
		$this->flashMessage("Item s ID $postId byl zviditelněn.", "success");	
		$this->redrawControl('postsList'); 
		$this->redrawControl('flashMessages');			
	}

	public function handleInactive($postId){
		if(!is_numeric($postId)){
			$this->flashMessage("Nehrabej se, prosím, v URL.", "danger");
			return;
		}
		if(!$this->isAjax()){
			$this->redirect("this");
		}

		$this->rm->setActive($postId, 0);
		$this->flashMessage("Item s ID $postId byl zneviditelněn.", "warning");
		$this->redrawControl("postsList");	
		$this->redrawControl('flashMessages');			
	}

	public function handleRemovePost($postId){
		if(!$this->isAjax()){
	    $this->redirect("this");
		}
		$post = $this->rm->getById($postId)->fetch();
		$this->rm->removeItem($postId);	
		//$this->payload->deleteItem = true; 
		
		$this->flashMessage("Success.", "success");

		$this->redrawControl('flashMessages');					
		$this->redrawControl('postsList');		
	}

	public function handleChange($page)
	{
		if(!$this->isAjax()){
			$this->redirect("this");
		}
		$this->paginator->setPage($page);
		$this->redrawControl('postsList');
		return;
	}


	public function formCancelled()
	{
		$this->redirect(':Admin:Results:');
	}


}
