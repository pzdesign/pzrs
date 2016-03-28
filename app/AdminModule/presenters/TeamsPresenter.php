<?php

namespace AdminModule;

use Nette,
Nette\Application\UI,
Nette\Forms\Controls,
Nette\Application\UI\Form,
Nette\Utils\Image,
Nette\Utils\Strings,
Nette\Utils\Finder;


class TeamsPresenter extends BasePresenter
{
	private $paginator;
	private $connection;
	private $posts = array();
	private $cleanCache;

	public function startup() {
		parent::startup();
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}
	}


	public function actionDefault($page = 1) {

		$paginator = new Nette\Utils\Paginator;
		$paginator->setItemCount($this->tm->getPostsCount($this->getView()));
		$paginator->setItemsPerPage(10);
		$paginator->setPage($page);
		$length = $paginator->getLength();

		if($page < 1 || $page > $length) {
			$this->flashMessage("Stránka musí být mezi 1 a $length.");
			$paginator->setPage(1);
		}
		$this->paginator = $paginator;
		$this->template->paginator = $this->paginator;		
		$this->template->absImagePath = $this->context->parameters['wwwDir'];  

		$allPosts = $this->tm->getPostsLimited($this->paginator->getLength(), $this->paginator->getOffset())->order('id DESC');
		$this->template->posts = $allPosts; 

		$this->template->teamATitleCheck = false;
		$this->template->teamALogoUploadClick = false;
		$this->template->teamALogoName = 'empty';		
		$this->template->teamALogoUploadDone = false;	
		$this->template->teamALogoFormFail = false;	
		//$this->redrawControl('addNewResult');
	}

	public function renderDefault()
	{      
		$this->template->paginator = $this->paginator;
		$allPosts = $this->tm->getPostsLimited($this->paginator->getLength(), $this->paginator->getOffset())->order('id DESC');
    	$this->template->posts = $allPosts; 
	}	

	protected function createComponentAddNewResultForm(){
		$form = new Form;
		$form->addText("teamA");

		$form->addHidden("logoTeamA", 'Logo A:')->setAttribute('id', 'uploadImgNameA')->setAttribute('readonly')->setDefaultValue(null);
		$form->addHidden('slug');
		$form->addHidden('edited_at');
		$form->addSubmit('submit', 'SAVE')
		->onClick[] = [$this, 'handleTeamsLogosSave'];

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
	    if (strlen($values->teamA) < 3) { // validační podmínka    	
	    	$form['teamA']->addError('Title must have atleast 3 char.');
	    }

	    if (!$values->logoTeamA) { // validační podmínka    	
	    	$this['teamALogoUpload']['teamALogo']->addError('Logo must be uploaded');
	    	$this->redrawControl('teamALogoForm');	       	      
	    }
	    $this->redrawControl('addNewResultSnippet');
	    $this->redrawControl('flashMessages');


	}

	public function addNewResultFormSubmit($form){
		if(!$this->isAjax()){
			$this->redirect('Admin:default');
		}	
		$values = $form->getValues();

		$created_at = date("Y-m-d H:i:s");	

		$values->logoTeamA = str_replace('storage/','',$values->logoTeamA);

		$query = $this->tm->addItem($values->teamA, $values->logoTeamA, $created_at);
		if($query) {
			$form->setValues(array(), TRUE); 

			$this->payload->resetForm = true; 

			$this->redrawControl('teamALogoForm');			  				  		
			$this->redrawControl('addNewTeamSnippet');			  		
			$this->redrawControl('postsList');	
	   		$this->paginator->setItemCount($this->tm->getPostsCount());

		} else {
			$this->flashMessage("ERROR", "danger");
		}
	}

	protected function createComponentTeamALogoUpload(){
		$form = new Form;
		$form->addUpload("teamALogo", "Logo A:")
		->addRule(Form::IMAGE, "Lze vložit pouze obrázky")
		->setAttribute('placeholder', 'napište email');

		$form->addSubmit("uploadA", "Upload");
		//$form->onValidate[] = $this->TeamALogoUploadCheck;
		$form->onSuccess[] = $this->TeamALogoUploadSubmit;
		return $form;
	}   

	public function TeamALogoUploadSubmit(UI\Form $form, $values){
		$values = $form->getValues();

		if(!$this->isAjax()){
			$this->redirect('this');
		}	    
    	//$form->setValues(array(), TRUE);	
		//$this->redrawControl('teamALogoForm');
		

		$dir = 'upload/myTeams/storage/';
		$uploadDir = 'upload/myTeams/';

		$name = $values->teamALogo->getName();
		$path = $dir.$name;
		$ext = '.jpg';

		$explode = explode(".", $name);
		$filetype = $explode[1];

		if ($filetype == 'jpg') {
			$ext = '.jpg';
		} else
		if ($filetype == 'jpeg') {
			$ext = '.jpeg';
		} else
		if ($filetype == 'png') {
			$ext = '.png';
		} else
		if ($filetype == 'gif') {
			$ext = '.gif';
		}

		$name = str_replace($ext,'',$name);
		$name = Strings::webalize($name);
		$path = $dir.$name.$ext;
		$uploadDir = $uploadDir.$name.$ext;

		if(file_exists($path) || file_exists($uploadDir)){

			$form['teamALogo']->addError("Foto již existuje!");
			$this->redrawControl('flashMessages');
			$this->redrawControl('teamALogoForm');

		} else {
			$values->teamALogo->move($path);

			$image = Image::fromFile($path);
			$image->resize(320, 180);
			$image->sharpen();

			$image->save($path,80, Image::JPEG);
			$this->flashMessage("Foto bylo nahráno", "success");
			$this->redrawControl('flashMessages');
			$this->template->teamALogoUploadClick = true;			
			$this->template->teamALogoUploadDone = true;
			$this->template->uploadDone = true;
			
			$this->template->imgName = $path;
			$this->template->uploadDone = true;	
   			$this->redrawControl('uploadForm');
			
			$this->template->teamALogoName =  $path;		
			$this->redrawControl('teamALogoForm');
			//$this->redrawControl('addNewResultSnippet');	
		}	
	}

	public function TeamALogoUploadCheck(UI\Form $form, $values){
	}

	protected function createComponentEditPostForm(){
		$form = new Form;

		$form->addText("teamA", 'Team name:');

		$form->addText("logoTeamA", 'Logo A:')->setAttribute('id', 'uploadImgNameA')->setAttribute('readonly')->setDefaultValue(null);

		$form->addSubmit("submit", "Uložit změny")
				->onClick[] = $this->handleSaveTeamLogoInEditMode;

		$form->addSubmit('cancel', 'Zavřít')
				->setValidationScope([])
				->onClick[] = [$this, 'formCancelled'];

		$form->onValidate[] = $this->editPostFormCheck;
		$form->onSuccess[] = $this->editPostFormSubmitted;

		return $form;
	}

	public function editPostFormCheck(UI\Form $form, $values){
		if(!$this->isAjax()){
			$this->redirect(':Admin:Results:');
		}

		$values = $form->getValues();
	    if (strlen($values->teamA) < 3) { // validační podmínka    	
	    	$form['teamA']->addError('Title must have atleast 3 char.');
	    }

	    if (!$values->logoTeamA) { // validační podmínka    	
	    	$this['teamALogoUpload']['teamALogo']->addError('Logo must be uploaded');
	    	$this->redrawControl('teamALogoForm');	       	      
	    }

	    $this->redrawControl('editPostForm');
	    $this->redrawControl('flashMessages');	

	}

	public function editPostFormSubmitted(UI\Form $form, $values){
		$id = $this->getParameter('id');

		$values = $form->getValues();

		$values->active = 0;
		$values->slug = Strings::webalize($values->teamA);	
		$this->tm->editPost($id,$values->teamA,$values->logoTeamA, $values->active);

		$this->flashMessage("Team $values->teamA byl upraven", "success");

		if(!$this->isAjax()){
			$this->redirect(':Admin:Teams:');
		}

		//$this->handleSaveTeamLogoInEditMode();	
		//	
		$this->redrawControl('teamALogoForm');	
		$this->redrawControl('flashMessages');

	}



	public function actionEditPost($id)
	{

		$this->template->absImagePath = $this->context->parameters['wwwDir']; 
		$this->template->checkAddFormValid = true; 
		$this->template->imgName = null;
		$this->template->removeDone = false;  		
		$this->template->imgRemoved = false;        
		$this->template->uploadDone = false;
		$this->template->editDone = false;			
		$post = $this->tm->getById($id)->fetch();
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

		$this->tm->setActive($postId, 1);

		$this->flashMessage("Item s ID $postId byl zviditelněn.", "success");	
		if(!$this->isAjax()) {
			$this->redirect("this");
		}
		$this->redrawControl('flashMessages'); 	
		$this->redrawControl('postsList'); 	
	}

	public function handleInactive($postId){
		if(!is_numeric($postId)){
			$this->flashMessage("Nehrabej se, prosím, v URL.", "danger");
			return;
		}

		$this->tm->setActive($postId, 0);

		$this->flashMessage("Item s ID $postId byl zneviditelněn.", "warning");
		if(!$this->isAjax()){
			$this->redirect("this");
		}	
		$this->redrawControl('flashMessages'); 	
		$this->redrawControl("postsList");		
	}

	public function handleRemovePost($postId){
		if(!$this->isAjax()){
			$this->redirect("this");
		}

		$post = $this->tm->getById($postId)->fetch();
		//$this->flashMessage("removed. $post->teamA", "danger");	
		$matches = $this->rm->findBy(array('teamA' => $post->teamA));
		foreach ($matches as $key => $match) {
			$this->flashMessage("disabled match. $match->id", "info");	
			$this->rm->setCanBeEdited($match->id);	
		}

		$this->tm->removeItem($postId);	
		$this->payload->deleteItem = true; 
		$this->flashMessage("removed. $postId", "success");	

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
		$folder = 'upload/myTeams/storage/';
		if (file_exists($folder)) {
			foreach (Finder::findFiles('*')->in($folder) as $key => $file) {
				$this->flashMessage("smazáno $file.", 'danger');   
				unlink($file);

			}
		$this->redirect(':Admin:Teams:');	
		} else {
			$this->redirect(':Admin:Teams:');		
		}
	}

	public function removeLogosFromStorage()
	{
		$dir = 'upload/logos/storage';
		$dirNew = 'upload/logos/';

		foreach (Finder::findFiles('*.jpg','*.png','*.JPG','*.PNG')->in($dir) as $key => $file) {
			$nfile = str_replace('/storage','',$file);
			if(!file_exists($nfile)){
				unlink($file);  
				$this->flashMessage("Cache byla vyprázdněna o tyto soubory. $file", "warning");	   						  
			} else {      	  				
				$this->flashMessage("Naše databáze již obsahuje tento soubor", "danger");

			}

		}
		$this->redrawControl('flashMessages');
	}

	public function handleTeamsLogosSave()
	{
		$dir = 'upload/myTeams/storage/';
		$dirNew = 'upload/myTeams/';

		if(!$this->isAjax()){
			$this->redirect("this");
		}

		foreach (Finder::findFiles('*.jpg','*.png','*.JPG','*.PNG')->in($dir) as $key => $file) {
			$nfile = str_replace('/storage','',$file);
			if(!file_exists($nfile)){
				copy($file, $nfile);
				unlink($file);     				
				$this->redrawControl('postsList');	
				$this->redrawControl('flashMessages'); 
			} else {
				$this->redrawControl('flashMessages');        	  				
				$this->flashMessage("Naše databáze již obsahuje tento soubor", "danger");

			}
		} 
	}

    public function handleRemoveCacheImg($imgRealName){
   		if(!$this->isAjax()){
	   	$this->redirect("this");
		} 	    	
    	if(file_exists($imgRealName)){
    	    $this->flashMessage("Obrazek byl odstranen.", "success"); 
    		unlink($imgRealName);    	    
    	}else {
    		$this->flashMessage("CHYBA.", "danger"); 		
    	}
		
    	//$this->template->post = $post;
		$this->template->removeDone = true;
    	$this->redrawControl("teamALogoForm");     	 
    	$this->redrawControl("editPostForm");     	 
    	//return;
    }


    public function handleRemoveImg($postId){

   		if(!$this->isAjax()){
	   	$this->redirect("this");
		} 
    	$post = $this->tm->getById($postId)->fetch();

    	if(file_exists($post->teamALogo)){
    	    $this->flashMessage("Obrazek byl odstranen.", "success"); 
    		$this->tm->delImg($postId,$post->teamALogo);     	    
    		unlink($post->teamALogo);    	    
    	}else {
    		$this->flashMessage("CHYBA.", "danger"); 		
    	}    
    	//unlink($post->img); 		 
		$this->template->removeDone = true;
		$this->redrawControl("teamALogoForm"); 
    	$this->redrawControl("flashMessages"); 
 	    return;  		
    }


  public function handleSaveTeamLogoInEditMode()
    {
    	$postId = $this->getParameter("id");
    	$dir = 'upload/myTeams/storage';
    	$dirNew = 'upload/myTeams/';
    	if($postId) {
    		$post = $this->tm->getById($postId)->fetch();    		
    	}

		if(!$this->isAjax()){
	    $this->redirect("this");
		}

        foreach (Finder::findFiles('*')->in($dir) as $key => $file) {
            $nfile = str_replace('/storage','',$file);
   			if(!file_exists($nfile)){
    			if($postId){  

    				$this->tm->updateImg($postId, $file, $nfile);   				
        			copy($file, $nfile);
   					unlink($file);  
					$this->template->editDone = true;  	
					$this->template->postAdded = true; 
        			$this->flashMessage("přesunuto $dir -> $dirNew -> $nfile", "info"); 					  					       			
        			$this->redirect(':Admin:Teams:');
 					$this->redrawControl('teamALogoForm');	
    				$this->redrawControl('flashMessages');
    				
        		}
        		
   			} else {
        		$this->flashMessage("Naše databáze již obsahuje tento soubor", "danger");
   			}

 		}   		  	
    }
}
