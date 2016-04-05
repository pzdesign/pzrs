<?php

namespace AdminModule;

use Nette;
use Nette\Application\UI;
use Nette\Forms\Controls;
use Nette\Application\UI\Form;
use Nette\Utils\Image;
use Nette\Utils\Strings;
use Nette\Utils\Finder;

class PlayersPresenter extends BasePresenter
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
         //$this->clearPlayersStorage();
    }


    public function actionDefault($page = 1)
    {
        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($this->plm->getPostsCount($this->getView()));
        $paginator->setItemsPerPage(10);
        $paginator->setPage($page);
        $length = $paginator->getLength();

        if ($page < 1 || $page > $length) {
            //$this->flashMessage("Stránka musí být mezi 1 a $length.");
            $paginator->setPage(1);
        }
        $this->paginator = $paginator;
        $this->template->paginator = $this->paginator;
        $this->template->absImagePath = $this->context->parameters['wwwDir'];

        $allPosts = $this->plm->getPostsLimited($this->paginator->getLength(), $this->paginator->getOffset())->order('id DESC');
        $this->template->posts = $allPosts;

        $this->template->addPlayerFormTitleCheck = false;
        $this->template->playerPhotoName = 'empty';

        $this->template->playerPhotoFormUploadClicked = false;
        $this->template->playerPhotoFormUploadDone = false;
        $this->template->playerPhotoFormUploadFail = false;
        $this->template->playerPhotoRemoved = false;  
        $this->template->playerPhotoRemovedInfo = false;              
    }

    public function renderDefault()
    {
        $this->template->paginator = $this->paginator;
        $allPosts = $this->plm->getPostsLimited($this->paginator->getLength(), $this->paginator->getOffset())->order('id DESC');
        $this->template->posts = $allPosts;
    }

    protected function createComponentAddNewPlayerForm()
    {
        $form = new Form;
        $form->addText("nickName", "Nickname: ")->addRule(Form::FILLED, 'Must be filled')->addRule(Form::MIN_LENGTH, 'min length is %d letters', 3);
        $form->addText("firstName", "Firstname: ");
        $form->addText("lastName", "Lastname: ");

        $form->addText("mouse", "Mouse: ");
        $form->addText("keyboard", "Keyboard: ");
        $form->addText("headphones", "Headphones: ");
        $form->addText("cpu", "CPU: ");
        $form->addText("gpu", "GPU: ");
        $form->addText("sensitivity", "Sensitivity: ");
        $form->addText("resolution", "Resolution: ");
        $form->addText("facebook", "Facebook: ");
        $form->addText("twitch", "Twitch: ");
        $form->addText("twitter", "Twitter: ");
        $form->addText("steam", "Steam: ");

        $form->addHidden("playerPhotoInForm")->setAttribute('id', 'playerPhoto')->setAttribute('readonly')
             ->setDefaultValue(null)->addRule(Form::FILLED, 'Must be filled');

        $form->addSubmit('submit', 'SAVE');
        $form->addSubmit('cancel', 'CLOSE')
            ->onClick[] = [$this, 'formCancelled'];

        $form->onValidate[] = $this->addNewPlayerFormCheck;
        $form->onSuccess[] = $this->addNewPlayerFormSubmit;
        return $form;
    }

    public function addNewPlayerFormCheck($form)
    {
        $values = $form->getValues();
        $this->redrawControl('addNewPlayerFormSnippet');   
        //$this->template->playerPhotoFormUploadFail = true;
        if(!$values->playerPhotoInForm){
            $this['playerPhotoFormUpload']['playerPhoto']->addError('Must be filled');
            $this->redrawControl('playerPhotoFormUploadSnippet');
        }  
    }

    public function addNewPlayerFormSubmit(UI\Form $form, $values)
    {
        if (!$this->isAjax()) {
            $this->redirect('Admin:default');
        }
        $values = $form->getValues();

        $values->playerPhotoInForm = str_replace('storage/', '', $values->playerPhotoInForm);
        $this->plm->addItem($values->nickName, $values->firstName, $values->lastName, $values->playerPhotoInForm,
            $values->mouse, $values->keyboard,$values->headphones, $values->cpu, $values->gpu, $values->sensitivity, $values->resolution,
            $values->facebook, $values->twitch, $values->twitter, $values->steam);
        $this->flashMessage("SUCCESS", "success");
           
        $this->payload->resetForm = true;
        $form->setValues(array(), true);
        $this->redrawControl('addNewPlayerForm');
        $this->redrawControl('addNewPlayerFormSnippet');
        $this->handlePlayerPhotoSave();
        $this->paginator->setItemCount($this->plm->getPostsCount());
        $this->redrawControl('postsList');
        //$this->payload->resetForm = true;
        $this['playerPhotoFormUpload']->setValues(array(), true);
        $this->redrawControl('playerPhotoFormUploadSnippet');         
    }

    protected function createComponentPlayerPhotoFormUpload()
    {
        $form = new Form;
        $form->addUpload("playerPhoto", "Player photo:")
                ->addRule(Form::IMAGE, "Only img with .jpg and .png can be selected")
                ->setAttribute('placeholder', 'napište email');

        $form->addSubmit("playerPhotoUploadSubmit", "Upload");
             //->onClick[] = [$this, 'playerPhotoFormUploadSubmit'];

        $form->onValidate[] = $this->playerPhotoFormUploadCheck;
        $form->onSuccess[] = $this->playerPhotoFormUploadSubmit;
        return $form;
    }

    public function playerPhotoFormUploadCheck(UI\Form $form, $values)
    {
        $this->redrawControl('playerPhotoFormUploadSnippet');
    }

    public function playerPhotoFormUploadSubmit(UI\Form $form, $values)
    {
        if (!$this->isAjax()) {
            $this->redirect('this');
        }
            
        $values = $form->getValues();

        $dir = 'upload/players/storage/';
        $uploadDir = 'upload/players/';

        $name = $values->playerPhoto->getName();
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
            //$this->template->playerPhotoFormUploadFail = true;
            //$this->template->playerPhotoFormUploadClicked = true;
            $form['playerPhoto']->addError("Photo already exists!");
            $this->template->playerPhotoFormUploadFail = true;
            $this->redrawControl('flashMessages');
            $this->redrawControl('playerPhotoFormUploadSnippet');
        } else {
            $values->playerPhoto->move($path);
            $image = Image::fromFile($path);
            $image->resize(320, 180);
            $image->sharpen();
            $image->save($path, 80, Image::JPEG);
            //$this->redrawControl('flashMessages');
            $this->flashMessage("Foto bylo nahráno", "success");
            $this->template->playerPhotoFormUploadClicked = true;
            $this->template->playerPhotoFormUploadDone = true;
            $this->template->playerPhotoName = $path;
            $this->redrawControl('playerPhotoFormUploadSnippet');
        }
    }

    

    protected function createComponentEditPostForm()
    {
        $form = new Form;

        $form->addText("teamA", 'Team name:');

        $form->addText("logoTeamA", 'Logo A:')->setAttribute('id', 'uploadImgNameA')->setAttribute('readonly')->setDefaultValue(null);

        $form->addSubmit("submit", "Uložit změny");
                //->onClick[] = $this->handleSaveTeamLogoInEditMode;

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

    public function editPostFormSubmitted(UI\Form $form, $values)
    {
        $id = $this->getParameter('id');

        $values = $form->getValues();

        $values->active = 0;

        $this->em->editPost($id, $values->teamA, $values->logoTeamA, $values->active);

        $this->flashMessage("Team $values->teamA byl upraven", "success");

        if (!$this->isAjax()) {
            $this->redirect(':Admin:Enemy:');
        }
        $this->handleSaveTeamLogoInEditMode();
        $this->redrawControl('teamALogoForm');
        $this->redrawControl('flashMessages');
        $this->redirect(':Admin:Enemy:');
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
        $post = $this->em->getById($id)->fetch();
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

        $this->plm->setActive($postId, 1);

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

        $this->plm->setActive($postId, 0);

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
        $post = $this->plm->getById($postId)->fetch();
        $this->handleRemoveImg($postId);

        $this->plm->removeItem($postId);
    
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
        $folder = 'upload/players/storage/';

        if (file_exists($folder)) {
            foreach (Finder::findFiles('*')->in($folder) as $key => $file) {
                $this->flashMessage("smazáno $file.", 'danger');
                unlink($file);
                $this->redirect(':Admin:Enemy:');                
            }
        } else {
            $this->redirect(':Admin:Enemy:');
        }
       
    }

    public function removeLogosFromStorage()
    {
        $dir = 'upload/players/storage';
        $dirNew = 'upload/players/';

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

    public function handlePlayerPhotoSave()
    {
        $dir = 'upload/players/storage/';
        $dirNew = 'upload/players/';

        if (!$this->isAjax()) {
            $this->redirect("this");
        }
     
        foreach (Finder::findFiles('*.jpg', '*.png', '*.JPG', '*.PNG')->in($dir) as $key => $file) {
            $nfile = str_replace('/storage', '', $file);
            if (!file_exists($nfile)) {
                copy($file, $nfile);
                unlink($file);
                $this->redrawControl('postsList');
            } else {
                $this->flashMessage("Naše databáze již obsahuje tento soubor", "danger");                
                $this->redrawControl('flashMessages');
            }
        }
    }

    public function handleRemoveCacheImg($imgRealName)
    {
        if (!$this->isAjax()) {
            $this->redirect("this");
        }
        if (file_exists($imgRealName)) {
            unlink($imgRealName);
            $this->template->playerPhotoFormUploadClicked = false;
            $this->template->playerPhotoFormUploadDone = false;
            $this->template->playerPhotoRemoved = true;
            $this->template->playerPhotoRemovedInfo = true;
            $this->redrawControl('flashMessages');
            $this->redrawControl('playerPhotoFormUploadSnippet');


        } else {
            $this->flashMessage("CHYBA.", "danger");
        }
        return;
    }

    public function clearPlayersStorage()
    {
        $dir = 'upload/players/storage/';

        foreach (Finder::findFiles('*')->in($dir) as $key => $file) {
            if (file_exists($file)) {
                unlink($file);
                //$this->redirect(':Admin:Players:');
                $this->redrawControl('flashMessages');
                $this->flashMessage("Cleared cache $dir -> $file", "info");
            }
        }
    }


    public function handleRemoveImg($postId)
    {
        if (!$this->isAjax()) {
            $this->redirect("this");
        }
        $post = $this->plm->getById($postId)->fetch();

        if (file_exists($post->playerphoto)) {
            $this->flashMessage("Obrazek byl odstranen.", "success");
            $this->plm->delImg($postId, $post->playerphoto);
            unlink($post->playerphoto);
        } else {
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
        $dir = 'upload/players/storage';
        $dirNew = 'upload/players/';
        if ($postId) {
            $post = $this->em->getById($postId)->fetch();
        }

        if (!$this->isAjax()) {
            $this->redirect("this");
        }

        foreach (Finder::findFiles('*')->in($dir) as $key => $file) {
            $nfile = str_replace('/storage', '', $file);
            if (!file_exists($nfile)) {
                if ($postId) {
                    $this->em->updateImg($postId, $file, $nfile);
                    copy($file, $nfile);
                    unlink($file);
                    $this->template->editDone = true;
                    $this->template->postAdded = true;
                    $this->flashMessage("přesunuto $dir -> $dirNew -> $nfile", "info");
                    $this->redirect(':Admin:Enemy:');
                    $this->redrawControl('teamALogoForm');
                    $this->redrawControl('flashMessages');
                }
            } else {
                $this->flashMessage("Naše databáze již obsahuje tento soubor", "danger");
            }
        }
    }
}
