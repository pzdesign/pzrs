<?php

namespace AdminModule;

use Nette,
    Nette\Application\UI,
    Nette\Forms\Controls,
    Nette\Application\UI\Form,
    Nette\Utils\Image,
	Nette\Utils\Strings,
	Nette\Utils\Finder;

class AdminPresenter extends BasePresenter
{
	private $posts = array();

	private $paginator;
	private $range;
	private $connection;
	private $postAdded;
	private $imgCached;

	public function startup() {
		parent::startup();
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }		
	}

    public function actionDefault($page = 1){
	$paginator = new Nette\Utils\Paginator;
	$paginator->setItemCount($this->pm->getPostsCount($this->getView()));
	$paginator->setItemsPerPage(10);
	$paginator->setPage($page);
	$length = $paginator->getLength();
	
	if($page < 1 || $page > $length){
	    //$this->flashMessage("Stránka musí být mezi 1 a $length.");
	    $paginator->setPage(1);
	}
	$this->paginator = $paginator;
	$this->template->postAdded = false;	
	$this->template->postEdited = false;
	$this->template->uploadDone = false;	
	$this->template->imgName = '';
	$this->payload->resetForm = false;
	$this->payload->deleteItem = false; 
	$this->template->imgRemoved = false;
    $this->template->checkAddFormValid = false;     

	    $this->redrawControl('postsList');
    }



	public function renderDefault($postId)
	{


		$this->template->absImagePath = $this->context->parameters['wwwDir'];        
		$this->template->paginator = $this->paginator;
		$allPosts = $this->pm->getPostsLimited($this->paginator->getLength(), $this->paginator->getOffset())->order('id DESC');
    	$this->template->posts = $allPosts; 
	}


   public function handleEditPost($postId)
    {
	    if(!$this->isAjax()){
		$this->redirect("this");
	    }
    	$this->template->onePost = $this->pm->getById($postId)->fetch();	
		$this->flashMessage("Příspěvek $postId byl editovan", "success");  	
	    $this->redrawControl('editPostForm');
	    $this->redirect("this"); 
	    return;
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

    protected function createComponentAddPostForm(){
	$form = new Form;
	
	$form->addText('title', 'Titulek:')
			->setRequired("Nevyplnili jste všechna políčka");

	$form->addText("teaser")->setRequired("Nevyplnili jste všechna políčka");

    $form->addText('img','Foto:')->setAttribute('id', 'uploadImgName')->setAttribute('readonly');

    $form->addTextArea('body', 'Obsah:',55, 5)->setRequired()->setAttribute('class', 'mceEditor');

    $form->addHidden('slug');

    $form->addHidden('created_at');


    $form->getElementPrototype()->onsubmit('tinyMCE.triggerSave()');
	$form->addSubmit("submit", "Přidat")
		 ->onClick[] = [$this, 'handleMoveStorageFile'];

	$form->onSuccess[] = $this->addPostFormSubmitted;


	return $form;
    }

    
    public function addPostFormSubmitted(UI\Form $form, $values){
	$values = $form->getValues();
	$values->created_at = date("Y-m-d H:i:s");
    $values->slug = Strings::webalize($values->title);	
	$values->active = 0;
	if(!$this->isAjax()){
	    $this->redirect("this");
	}

	$checkTitle = $this->pm->findBy(array('title'=> $values->title))->count();
	if($checkTitle > 0) {
	$form->addError("EXISTUJE $checkTitle.$this->postAdded");
 	$this->template->checkAddFormValid = false;     
	$this->payload->resetForm2 = true;	
    //$this->redrawControl('uploadForm');	
    $this->redrawControl('addPostForm');	
	} else {
 	$values->img = str_replace('/storage','',$values->img);
	$this->pm->addItem($values->title, $values->teaser, $values->body, 
		$values->active, $values->slug, $values->created_at,$values->img);
	
	$this->flashMessage("Příspěvek $values->title byl přidán ", "success");

    $form->setValues(array(), TRUE); 
	$this->template->postAdded = true;	
	$this->payload->resetForm = true; 
 	$this->template->checkAddFormValid = true; 
 	$this->handleMoveStorageFile();
    $this->redrawControl('uploadForm');
    $this->redrawControl('addPostForm');
    $this->redrawControl('postsList');

	$this->paginator->setItemCount($this->pm->getPostsCount());
	}	
	return;
    }

     protected function createComponentAddPostFormUpload(){
	$form = new Form;
    $form->addUpload("img", "Obrázek")->addRule(Form::IMAGE, "Lze vložit pouze obrázky");
	$form->addSubmit("upload", "Nahrát");
	$form->onSuccess[] = $this->addPostFormUploadSubmitted;
	return $form;
    }   



    public function addPostFormUploadSubmitted(UI\Form $form, $values){
	if(!$this->isAjax()){
	    $this->redirect("this");
	}
	$values = $form->getValues();
	$dir = 'upload/posts/storage/';
	$uploadDir = 'upload/posts/';

	$name = $values->img->getName();
    $path = $dir.$name;
    $ext = '.jpg';
/////////////////////

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

////////////////////


	$name = str_replace($ext,'',$name);
	$name = Strings::webalize($name);
    $path = $dir.$name.$ext;
    $uploadDir = $uploadDir.$name.$ext;


    if(file_exists($path) || file_exists($uploadDir)){

    $this->flashMessage("Foto již existuje! STORAGE", "warning");

	} else {
    $values->img->move($path);

	$image = Image::fromFile($path);
	$image->resize(320, 180);
	$image->sharpen();


    
	$image->save($path,80, Image::JPEG);

	$this->flashMessage("Foto bylo nahráno", "success");

	$this->template->imgName = $path;
	$this->template->uploadDone = true;	
    $this->redrawControl('uploadForm');
   }
   return;
    }




    protected function createComponentEditPostForm(){
	$form = new Form;
	
	$form->addText("title", 'Titulek:')->setRequired("Nevyplnili jste všechna políčka");

	$form->addText("teaser", 'Náhled:')->setRequired("Nevyplnili jste všechna políčka");

    $form->addTextArea('body', 'Obsah:',55, 5)->setRequired()->setAttribute('class', 'mceEditor');

    $form->addHidden('slug');

    $form->addHidden('img')->setAttribute('id', 'uploadImgName')->setAttribute('readonly');

    $form->addHidden('edited_at');

    $form->getElementPrototype()->onsubmit('tinyMCE.triggerSave()');

	$form->addSubmit("submit", "Uložit změny")
	     ->onClick[] = [$this, 'handleMoveStorageFile'];
 
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
	$values->active = 0;
    $values->slug = Strings::webalize($values->title);	
	$this->pm->editPost($id,$values->title, $values->teaser, $values->body, $values->active, $values->slug, 
		$values->edited_at);
	
	$this->flashMessage("Příspěvek $values->title byl upraven", "success");
	
	if(!$this->isAjax()){
    $this->redirect('Admin:default');
	}

 	$this->template->editDone = true;  	
	$this->template->postAdded = true;

 	$this->redrawControl('uploadForm');	
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
        $post = $this->pm->getById($id)->fetch();
        if (!$post) {
            $this->error('Příspěvek nebyl nalezen');
        }
        $this->template->post = $post;

        $this['editPostForm']->setDefaults($post->toArray());
    }



    public function formCancelled()
    {
		$folder = 'upload/posts/storage/';

		if (file_exists($folder)) {
			foreach (Finder::findFiles('*')->in($folder) as $key => $file) {
				$this->flashMessage("smazáno $file.", 'danger');   
				unlink($file);     	
	
			}
			$this->redirect(':Admin:Admin:');					
		} else {
			$this->redirect(':Admin:Admin:');
		}
    }


    public function handleActive($postId){
	if(!is_numeric($postId)){
	    $this->flashMessage("Nehrabej se, prosím, v URL.", "danger");
	    return;
	}
	
	$this->pm->setActive($postId, 1);
	
	$this->flashMessage("Item s ID $postId byl zviditelněn.", "success");	
	if(!$this->isAjax()){
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
	
	$this->pm->setActive($postId, 0);
	
	$this->flashMessage("Item s ID $postId byl zneviditelněn.", "success");
	if(!$this->isAjax()){
	    $this->redirect("this");
	}	
    $this->redrawControl('flashMessages'); 	
	
	$this->redrawControl("postsList");		
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
    	$this->redrawControl("uploadForm");     	 
    	//return;
    }


    public function handleRemoveImg($postId){

   		if(!$this->isAjax()){
	   	$this->redirect("this");
		} 
    	$post = $this->pm->getById($postId)->fetch();

    	if(file_exists($post->img)){
    	    $this->flashMessage("Obrazek byl odstranen.", "success"); 
    		$this->pm->delImg($postId,$post->img);     	    
    		unlink($post->img);    	    
    	}else {
    		$this->flashMessage("CHYBA.", "danger"); 		
    	}    
    	//unlink($post->img); 		 
		$this->template->removeDone = true;
		$this->redrawControl("uploadForm"); 
    	$this->redrawControl("flashMessages"); 
 	    return;  		
    }

    public function handleRemovePost($postId){
	if(!is_numeric($postId)){
	    $this->flashMessage("Nehrabej se, prosím, v URL.", "danger");
	    return;
	}
	//$this->flashMessage("Post byl odstraněn.",'success');  
	if(!$this->isAjax()){
	    $this->redirect("this");
	}
	$post = $this->pm->getById($postId)->fetch();
	$filePath = $post->img;
	if(file_exists($filePath)){
	unlink($filePath);
	$this->flashMessage("existuje",'info'); 
	}
	else 
	{
	$this->flashMessage("neexistuje",'info');  
	}	

	$this->pm->removeItem($postId);	
	$this->payload->deleteItem = true; 
	//} 
	$this->redrawControl('postsList');

    }


  public function handleMoveStorageFile()
    {
    	$postId = $this->getParameter("id");
    	$dir = 'upload/posts/storage';
    	$dirNew = 'upload/posts/';
    	if($postId) {
    		$post = $this->pm->getById($postId)->fetch();    		
    	}

		if(!$this->isAjax()){
	    $this->redirect("this");
		}
 		if($this->template->checkAddFormValid){

        foreach (Finder::findFiles('*')->in($dir) as $key => $file) {
            $nfile = str_replace('/storage','',$file);
   			if(!file_exists($nfile)){
    			if($postId){  

    				$this->pm->updateImg($postId, $file, $nfile);   				
        			$this->flashMessage("přesunuto $dir -> $dirNew -> $nfile", "info"); 
        			copy($file, $nfile);
   					unlink($file);         			
        			$this->redirect('Admin:default');

        		} else {
    				$this->template->postAdded = true;
    				$this->imgCached = $nfile;
        			copy($file, $nfile);
   					unlink($file);     				
 					$this->redrawControl('uploadForm');	
    				$this->redrawControl('flashMessages');
        		}
        		
   			} else {
        		$this->flashMessage("Naše databáze již obsahuje tento soubor", "danger");
   			}

 		} 

		} else {
        		$this->flashMessage("chyba existuje", "danger");

 		}  		  	
    }
}
