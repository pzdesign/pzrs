<?php

namespace AdminModule;

use Nette;
use Nette\Application\UI;
use Nette\Forms\Controls;
use Nette\Application\UI\Form;
use Nette\Utils\Image;
use Nette\Utils\Strings;
use Nette\Utils\Finder;

class MapsPresenter extends BasePresenter
{
    private $paginator;
    private $connection;
    private $posts = array();
    private $cleanCache;

    public function startup()
    {
        parent::startup();
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }


    public function actionDefault($page = 1)
    {
        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($this->mm->getPostsCount($this->getView()));
        $paginator->setItemsPerPage(10);
        $paginator->setPage($page);
        $length = $paginator->getLength();

        if ($page < 1 || $page > $length) {
            $this->flashMessage("Stránka musí být mezi 1 a $length.");
            $paginator->setPage(1);
        }
        $this->paginator = $paginator;
        $this->template->paginator = $this->paginator;
        $this->template->absImagePath = $this->context->parameters['wwwDir'];

        $allPosts = $this->mm->getPostsLimited($this->paginator->getLength(), $this->paginator->getOffset())->order('id DESC');
        $this->template->posts = $allPosts;

        $this->template->mapTitleCheck = false;
        $this->template->mapImgUploadClick = false;
        $this->template->mapImgName = 'empty';
        $this->template->mapImgUploadDone = false;
        $this->template->mapImgFormFail = false;
        //$this->redrawControl('addNewMap');
    }

    public function renderDefault()
    {
        $this->template->paginator = $this->paginator;
        $allPosts = $this->mm->getPostsLimited($this->paginator->getLength(), $this->paginator->getOffset())->order('id DESC');
        $this->template->posts = $allPosts;
    }

    protected function createComponentAddNewMapForm()
    {
        $form = new Form;
        $form->addText("mapName");

        $form->addText("mapImgHidden")->setAttribute('id', 'uploadImg')->setAttribute('readonly')->setDefaultValue(null);
        $form->addSubmit('submit', 'SAVE');

        $form->addSubmit('cancel', 'CLOSE')
        ->onClick[] = [$this, 'formCancelled'];

        $form->onValidate[] = $this->addNewMapFormCheck;
        $form->onSuccess[] = $this->addNewMapFormSubmit;
        return $form;
    }

    public function addNewMapFormCheck($form)
    {
        if (!$this->isAjax()) {
            $this->redirect('Admin:default');
        }

        $values = $form->getValues();

        if (strlen($values->mapName) < 3) { // validační podmínka    	
            $form['mapName']->addError('Title must have atleast 3 char.');
        }

        if (!$values->mapImgHidden) { // validační podmínka    	
            $this['mapImgUpload']['mapImg']->addError('Img must be uploaded');
            $this->redrawControl('mapImgUploadSnippet');
        }
        $this->redrawControl('addNewMapSnippet');
        $this->redrawControl('flashMessages');
    }

    public function addNewMapFormSubmit($form)
    {
        if (!$this->isAjax()) {
            $this->redirect('Admin:default');
        }
        $values = $form->getValues();
        if ($values->mapImgHidden) {
            $active = 0;
            $values->mapImgHidden = str_replace('storage/', '', $values->mapImgHidden);
            $query = $this->mm->addItem($values->mapName, $values->mapImgHidden, $active);
            if ($query) {
                $this->payload->resetForm = true;
                $form->setValues(array(), true);
                $this->redrawControl('mapImgUploadSnippet');
                $this->redrawControl('addNewImgSnippet');
                $this->redrawControl('postsList');
                
                $this->paginator->setItemCount($this->mm->getPostsCount());
                $this->handleMapsImgsSave();
            } else {
                $this->flashMessage("ERROR", "danger");
            }
        }
    }

    protected function createComponentMapImgUpload()
    {
        $form = new Form;
        $form->addUpload("mapImg", "Img :")
        ->addRule(Form::IMAGE, "Lze vložit pouze obrázky")
        ->setAttribute('placeholder', 'napište email');

        $form->addSubmit("upload", "Upload");
        $form->onValidate[] = $this->MapImgUploadCheck;
        $form->onSuccess[] = $this->MapImgUploadSubmit;
        return $form;
    }

    public function MapImgUploadSubmit(UI\Form $form, $values)
    {
        $values = $form->getValues();

        if (!$this->isAjax()) {
            $this->redirect('this');
        }
        //$form->setValues(array(), TRUE);	
        //$this->redrawControl('mapImgUploadSnippet');


        $dir = 'upload/maps/storage/';
        $uploadDir = 'upload/maps/';

        $name = $values->mapImg->getName();
        $path = $dir.$name;
        $ext = '.jpg';

        $explode = explode(".", $name);
        $filetype = $explode[1];

        if ($filetype == 'jpg') {
            $ext = '.jpg';
        } elseif ($filetype == 'jpeg') {
            $ext = '.jpeg';
        } elseif ($filetype == 'png') {
            $ext = '.png';
        } elseif ($filetype == 'gif') {
            $ext = '.gif';
        }

        $name = str_replace($ext, '', $name);
        $name = Strings::webalize($name);
        $path = $dir.$name.$ext;
        $uploadDir = $uploadDir.$name.$ext;

        if (file_exists($path) || file_exists($uploadDir)) {
            $form['mapImg']->addError("Foto již existuje!");
            $this->redrawControl('flashMessages');
            $this->redrawControl('mapImgUploadSnippet');
        } else {
            $values->mapImg->move($path);

            $image = Image::fromFile($path);
            $image->resize(320, 180);
            $image->sharpen();

            $image->save($path, 80, Image::JPEG);
            $this->flashMessage("Foto bylo nahráno", "success");
            $this->redrawControl('flashMessages');
            $this->template->mapImgUploadClick = true;
            $this->template->mapImgUploadDone = true;
            $this->template->uploadDone = true;
            
            $this->template->imgName = $path;
            $this->template->uploadDone = true;
            $this->redrawControl('mapImgUpload');
            
            $this->template->mapImgName =  $path;
            $this->redrawControl('mapImgUploadSnippet');
            //$this->redrawControl('addNewMapSnippet');	
        }
    }

    public function MapImgUploadCheck(UI\Form $form, $values)
    {
    }

    protected function createComponentEditPostForm()
    {
        $form = new Form;

        $form->addText("mapName", 'Team name:');

        $form->addText("mapImgHidden", 'Img A:')->setAttribute('id', 'uploadImgName')
        ->setAttribute('readonly')->setDefaultValue(null);

        $form->addSubmit("submit", "Uložit změny");
                //->onClick[] = $this->handleSaveTeamImgInEditMode;

        $form->addSubmit('cancel', 'Zavřít')
        ->setValidationScope([])
        ->onClick[] = [$this, 'formCancelled'];

        $form->onValidate[] = $this->editPostFormCheck;
        $form->onSuccess[] = $this->editPostFormSubmitted;

        return $form;
    }

    public function editPostFormCheck(UI\Form $form, $values)
    {
        if (!$this->isAjax()) {
            $this->redirect(':Admin:Maps:');
        }

        $values = $form->getValues();
        if (strlen($values->mapName) < 3) { // validační podmínka    	
            $form['mapName']->addError('Title must have atleast 3 char.');
        }

        if (!$values->mapImgHidden) { // validační podmínka    	
            $this['mapImgUpload']['mapImg']->addError('Img must be uploaded');
            $this->redrawControl('mapImgUploadSnippet');
        }

        $this->redrawControl('editPostForm');
        $this->redrawControl('flashMessages');
    }

    public function editPostFormSubmitted(UI\Form $form, $values)
    {
        $id = $this->getParameter('id');

        $values = $form->getValues();

        $values->active = 0;

        $this->mm->editPost($id, $values->mapName, $values->mapImgHidden, $values->active);

        $this->flashMessage("Team $values->mapName byl upraven", "success");

        if (!$this->isAjax()) {
            $this->redirect(':Admin:Maps:');
        }
        $this->handleSaveMapImgInEditMode();
        $this->redrawControl('mapImgUploadSnippet');
        $this->redrawControl('flashMessages');
        $this->redirect(':Admin:Maps:');
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
        $post = $this->mm->getById($id)->fetch();
        if (!$post) {
            $this->error('Příspěvek nebyl nalezen');
        }
        $this->template->post = $post;

        $this['editPostForm']->setDefaults($post->toArray());
    }


    public function handleActive($postId)
    {
        if (!is_numeric($postId)) {
            $this->flashMessage("Nehrabej se, prosím, v URL.", "danger");
            return;
        }

        $this->mm->setActive($postId, 1);

        $this->flashMessage("Item s ID $postId byl zviditelněn.", "success");
        if (!$this->isAjax()) {
            $this->redirect("this");
        }
        $this->redrawControl('flashMessages');
        $this->redrawControl('postsList');
    }

    public function handleInactive($postId)
    {
        if (!is_numeric($postId)) {
            $this->flashMessage("Nehrabej se, prosím, v URL.", "danger");
            return;
        }

        $this->mm->setActive($postId, 0);

        $this->flashMessage("Item s ID $postId byl zneviditelněn.", "warning");
        if (!$this->isAjax()) {
            $this->redirect("this");
        }
        $this->redrawControl('flashMessages');
        $this->redrawControl("postsList");
    }
    
    public function handleRemovePost($postId)
    {
        if (!$this->isAjax()) {
            $this->redirect("this");
        }
        $post = $this->mm->getById($postId)->fetch();
        $this->handleRemoveImg($postId);

        $matches = $this->rm->findBy(array('map1' => $post->mapName));
        foreach ($matches as $key => $match) {
            $this->flashMessage("disabled match. $match->id", "info");
            $this->rm->setCanBeEdited($match->id);
        }

        $this->mm->removeItem($postId);

        $this->payload->deleteItem = true;

        $this->flashMessage("Success.", "success");
        $this->redrawControl('flashMessages');
        $this->redrawControl('postsList');
    }

    public function handleChange($page)
    {
        if (!$this->isAjax()) {
            $this->redirect("this");
        }
        $this->paginator->setPage($page);
        $this->redrawControl('postsList');
        return;
    }


    public function formCancelled()
    {
        $folder = 'upload/maps/storage/';

        if (file_exists($folder)) {
            foreach (Finder::findFiles('*')->in($folder) as $key => $file) {
                $this->flashMessage("smazáno $file.", 'danger');
                unlink($file);
                $this->redirect(':Admin:Maps:');
            }
        } else {
            $this->redirect(':Admin:Maps:');
        }
    }

    public function removeImgsFromStorage()
    {
        $dir = 'upload/maps/storage';
        $dirNew = 'upload/maps/';

        foreach (Finder::findFiles('*.jpg', '*.png', '*.JPG', '*.PNG')->in($dir) as $key => $file) {
            $nfile = str_replace('/storage', '', $file);
            if (!file_exists($nfile)) {
                unlink($file);
                $this->flashMessage("Cache byla vyprázdněna o tyto soubory. $file", "warning");
            } else {
                $this->flashMessage("Naše databáze již obsahuje tento soubor", "danger");
            }
        }
        $this->redrawControl('flashMessages');
    }

    public function handleMapsImgsSave()
    {
        $dir = 'upload/maps/storage/';
        $dirNew = 'upload/maps/';

        if (!$this->isAjax()) {
            $this->redirect("this");
        }

        foreach (Finder::findFiles('*.jpg', '*.png', '*.JPG', '*.PNG')->in($dir) as $key => $file) {
            $nfile = str_replace('/storage', '', $file);
            if (!file_exists($nfile)) {
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

    public function handleRemoveCacheImg($imgRealName)
    {
        if (!$this->isAjax()) {
            $this->redirect("this");
        }
        if (file_exists($imgRealName)) {
            $this->flashMessage("Obrazek byl odstranen.", "success");
            unlink($imgRealName);
        } else {
            $this->flashMessage("CHYBA.", "danger");
        }
        
        //$this->template->post = $post;
        $this->template->removeDone = true;
        $this->redrawControl("mapImgUploadSnippet");
        $this->redrawControl("editPostForm");
        //return;
    }
/*
   public function removeImg($postId){

    	$post = $this->mm->getById($postId)->fetch();
     	$this->mm->delImg($postId,$post->mapImg);     	    
    	unlink($post->mapImg);     	
   }
*/
   public function handleRemoveImg($postId)
   {
       if (!$this->isAjax()) {
           $this->redirect("this");
       }
       $post = $this->mm->getById($postId)->fetch();

       if (file_exists($post->mapImg)) {
           $this->flashMessage("Obrazek byl odstranen.", "success");
           $this->mm->delImg($postId, $post->mapImg);
           unlink($post->mapImg);
       } else {
           $this->flashMessage("CHYBA.", "danger");
       }
        //unlink($post->img); 		 
    $this->template->removeDone = true;
       $this->redrawControl("mapImgUploadSnippet");
       $this->redrawControl("flashMessages");
       return;
   }


    public function handleSaveMapImgInEditMode()
    {
        $postId = $this->getParameter("id");
        $dir = 'upload/maps/storage';
        $dirNew = 'upload/maps/';
        if ($postId) {
            $post = $this->mm->getById($postId)->fetch();
        }

        if (!$this->isAjax()) {
            $this->redirect("this");
        }

        foreach (Finder::findFiles('*')->in($dir) as $key => $file) {
            $nfile = str_replace('/storage', '', $file);
            if (!file_exists($nfile)) {
                if ($postId) {
                    $this->mm->updateImg($postId, $file, $nfile);
                    copy($file, $nfile);
                    unlink($file);
                    $this->template->editDone = true;
                    $this->template->postAdded = true;
                    $this->flashMessage("přesunuto $dir -> $dirNew -> $nfile", "info");
                    $this->redirect(':Admin:Maps:');
                    $this->redrawControl('mapImgUploadSnippet');
                    $this->redrawControl('flashMessages');
                }
            } else {
                $this->flashMessage("Naše databáze již obsahuje tento soubor", "danger");
            }
        }
    }
}
