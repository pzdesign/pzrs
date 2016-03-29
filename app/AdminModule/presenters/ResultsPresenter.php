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
	private $teamsAArray = array();
	private $teamsBArray = array();
	private $mapsArray = array();

	public function startup() {
		parent::startup();
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}


		$teamsA = $this->tm->findAll();
		foreach($teamsA as $key => $teamA) {
		$teamsAArray[] = $teamA->teamA;
		}

		$teamsB = $this->em->findAll();
		foreach($teamsB as $key => $teamB) {
		$teamsBArray[] = $teamB->teamA;
		}


		$mapsFromManager = $this->mm->findAll();
		foreach($mapsFromManager as $key => $map) {
		$mapsArray[] = $map->mapName;
		}

		$this->teamsAArray = $teamsAArray;		
		$this->teamsBArray = $teamsBArray;
		array_unshift($mapsArray, 'None');		
		$this->mapsArray = $mapsArray;


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


		$form = new Form;

		$form->addSelect('teamA', 'Home:')->setItems($this->teamsAArray, FALSE);
		$form->addSelect('teamB', 'Enemy:')->setItems($this->teamsBArray, FALSE);

		$form->addSelect('map1', 'Map1:')->setItems($this->mapsArray, FALSE)
				->setAttribute('id', 'map1select');	

		$form->addSelect('map2', 'Map2:')->setItems($this->mapsArray, FALSE)
				->setAttribute('id', 'map2select');	

		$form->addSelect('map3', 'Map3:')->setItems($this->mapsArray, FALSE)
				->setAttribute('id', 'map3select');	

	 	/*
		$form->addSelect('map4', 'Map4:')->setItems($mapsArray, FALSE);
		$form->addSelect('map5', 'Map5:')->setItems($mapsArray, FALSE);
		*/
		$form->addText('resultAMap1', 'HM 1:');
		$form->addText('resultAMap2', 'HM 2:');
		$form->addText('resultAMap3', 'HM 3:');
		$form->addText('resultBMap1', 'EM 1:');
		$form->addText('resultBMap2', 'EM 2:');
		$form->addText('resultBMap3', 'EM 3:');


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

	    if ($values->map1 == 'None') { // validační podmínka    	
	        $form['map1']->addError('First map must be choosen. ');	      
	    }

	    if ($values->map1 != 'None' && $values->map2 != 'None' && $values->map3 != 'None') { // validační podmínka 
	        return true;	             	 
	          
	    } elseif ($values->map1 != 'None' && $values->map2 != 'None' && $values->map3 == 'None') {
	    	$form['map3']->addError('Why Iam empty?. ');
	    }


	    if ($values->map1 == 'None' && $values->map2 != 'None' && $values->map3 == 'None') { // validační podmínka    	
	        //$form['map2']->addError('If u choose 2nd map u have to choose 1st and 3rd too!. ');	      
	        $form['map3']->addError('Why Iam empty?. ');	      
	    } 


	    if ($values->map1 != 'None' && $values->map2 == 'None' && $values->map3 != 'None') { // validační podmínka    	
	        $form['map2']->addError('Why Iam empty?. ');	      
	        $form['map3']->addError('If u choose 3rd map u have to choose 2nd too!. ');	      
	    } 


	    if ($values->map1 == 'None' && $values->map2 != 'None' && $values->map3 != 'None') { // validační podmínka    	
	        $form['map1']->addError('If u choose 2nd and 3rd map u have to choose 1st too!. ');	      
      
	    } 

	    if ($values->map1 != 'None') { // validační podmínka   
	     	if ($values->resultAMap1 == null) {
	        	$form['resultAMap1']->addError(' ');	      
	    	}
	     	if ($values->resultBMap1 == null) {
	        	$form['resultBMap1']->addError(' ');	      
	    	}


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
		$canBeEdited = 1;

		$resultA = 0;
		$resultB = 0;
		$win = 'L';
			

		if($values->resultAMap1 > $values->resultBMap1) {
			$resultA++;
		} elseif($values->resultAMap1 < $values->resultBMap1) {
			$resultB++;
			}
		

		if($values->resultAMap2 > $values->resultBMap2) {
			$resultA++;
		} elseif($values->resultAMap2 < $values->resultBMap2) {
			$resultB++;
			}
	

		if($values->resultAMap3 > $values->resultBMap3) {
			$resultA++;
		} elseif($values->resultAMap3 < $values->resultBMap3) {
			$resultB++;
			}
	

		if($resultA > $resultB) {
			$win = 'W';
		} elseif ( $resultA == $resultB ) {
			$win = 'D';
			$resultA = 1;
			$resultB = 1;						
		}
			

		$query = $this->rm->addItem(
			$values->teamA, 
			$teamALogo->teamALogo, 
			$values->teamB, 
			$teamBLogo->teamALogo, 
			$values->map1, 
			$values->map2, 
			$values->map3, 
			$values->resultAMap1,			 
			$values->resultAMap2,			 
			$values->resultAMap3,			 
			$values->resultBMap1,			 
			$values->resultBMap2,			 
			$values->resultBMap3,			
			$resultA,			
			$resultB,			
			$win,			
			$created_at,
			$canBeEdited);
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

		$form = new Form;

		$form->addSelect('teamA', 'Team:')->setItems($this->teamsAArray, FALSE);
		$form->addSelect('teamB', 'Enemy:')->setItems($this->teamsBArray, FALSE);
		
		$form->addSelect('map1', 'Map1:')->setItems($this->mapsArray, FALSE);	
		$form->addSelect('map2', 'Map2:')->setItems($this->mapsArray, FALSE);
		$form->addSelect('map3', 'Map3:')->setItems($this->mapsArray, FALSE);

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


		$this->rm->editPost($id,
			$values->teamA, 
			$teamALogo->teamALogo, 
			$values->teamB, 
			$teamBLogo->teamALogo,
			$values->map1, 
			$values->map2, 
			$values->map3,			 
			$values->resultAMap1,			 
			$values->resultAMap2,			 
			$values->resultAMap3,			 
			$values->resultBMap1,			 
			$values->resultBMap2,			 
			$values->resultBMap3,			 
			$values->edited_at);

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
