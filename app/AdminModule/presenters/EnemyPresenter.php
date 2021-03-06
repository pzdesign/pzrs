<?php

namespace AdminModule;

use Nette;
use Nette\Application\UI;
use Nette\Forms\Controls;
use Nette\Application\UI\Form;
use Nette\Utils\Image;
use Nette\Utils\Strings;
use Nette\Utils\Finder;

class EnemyPresenter extends BasePresenter
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
        $paginator->setItemCount($this->em->getPostsCount($this->getView()));
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

        $allPosts = $this->em->getPostsLimited($this->paginator->getLength(), $this->paginator->getOffset())->order('id DESC');
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
        $allPosts = $this->em->getPostsLimited($this->paginator->getLength(), $this->paginator->getOffset())->order('id DESC');
        $this->template->posts = $allPosts;
    }

    protected function createComponentAddNewResultForm()
    {
        $form = new Form;
        $form->addText("teamA");

        $form->addText("logoTeamA")->setAttribute('id', 'uploadImgNameA')->setAttribute('readonly')->setDefaultValue(null);
        $form->addHidden('slug');
        $form->addHidden('edited_at');
        $form->addSubmit('submit', 'SAVE');

        $form->addSubmit('cancel', 'CLOSE')
            ->onClick[] = [$this, 'formCancelled'];

        $form->onValidate[] = $this->addNewResultFormCheck;
        $form->onSuccess[] = $this->addNewResultFormSubmit;
        return $form;
    }

    public function addNewResultFormCheck($form)
    {
        if (!$this->isAjax()) {
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

    public function addNewResultFormSubmit($form)
    {
        if (!$this->isAjax()) {
            $this->redirect('Admin:default');
        }
        $values = $form->getValues();
        if ($values->logoTeamA) {
            $created_at = date("Y-m-d H:i:s");
            $values->logoTeamA = str_replace('storage/', '', $values->logoTeamA);
            $query = $this->em->addItem($values->teamA, $values->logoTeamA, $created_at);
            if ($query) {
                $this->payload->resetForm = true;
                $form->setValues(array(), true);
                $this->redrawControl('teamALogoForm');
                $this->redrawControl('addNewTeamSnippet');
                $this->redrawControl('postsList');
        
                $this->paginator->setItemCount($this->em->getPostsCount());
                $this->handleTeamsLogosSave();
            } else {
                $this->flashMessage("ERROR", "danger");
            }
        }
    }

    protected function createComponentTeamALogoUpload()
    {
        $form = new Form;
        $form->addUpload("teamALogo", "Logo A:")
                ->addRule(Form::IMAGE, "Lze vložit pouze obrázky")
                ->setAttribute('placeholder', 'napište email');

        $form->addSubmit("uploadA", "Upload");
        $form->onValidate[] = $this->TeamALogoUploadCheck;
        $form->onSuccess[] = $this->TeamALogoUploadSubmit;
        return $form;
    }

    public function TeamALogoUploadSubmit(UI\Form $form, $values)
    {
        $values = $form->getValues();

        if (!$this->isAjax()) {
            $this->redirect('this');
        }
        //$form->setValues(array(), TRUE);	
        //$this->redrawControl('teamALogoForm');


        $dir = 'upload/enemy/storage/';
        $uploadDir = 'upload/enemy/';

        $name = $values->teamALogo->getName();
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
            $form['teamALogo']->addError("Foto již existuje!");
            $this->redrawControl('flashMessages');
            $this->redrawControl('teamALogoForm');
        } else {
            $values->teamALogo->move($path);

            $image = Image::fromFile($path);
            $image->resize(320, 180);
            $image->sharpen();

            $image->save($path, 80, Image::JPEG);
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

    public function TeamALogoUploadCheck(UI\Form $form, $values)
    {
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

        $this->em->setActive($postId, 1);

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

        $this->em->setActive($postId, 0);

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
        $post = $this->em->getById($postId)->fetch();
        $this->removeImg($postId);

        $matches = $this->rm->findBy(array('teamB' => $post->teamA));
        foreach ($matches as $key => $match) {
            $this->flashMessage("disabled match. $match->id", "info");
            $this->rm->setCanBeEdited($match->id);
        }

        $this->em->removeItem($postId);
    
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
        $folder = 'upload/enemy/storage/';

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
        $dir = 'upload/enemy/storage';
        $dirNew = 'upload/enemy/';

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

    public function handleTeamsLogosSave()
    {
        $dir = 'upload/enemy/storage/';
        $dirNew = 'upload/enemy/';

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
        $this->redrawControl("teamALogoForm");
        $this->redrawControl("editPostForm");
        //return;
    }

    public function removeImg($postId)
    {
        $post = $this->em->getById($postId)->fetch();
        $this->em->delImg($postId, $post->teamALogo);
        unlink($post->teamALogo);
    }

    public function handleRemoveImg($postId)
    {
        if (!$this->isAjax()) {
            $this->redirect("this");
        }
        $post = $this->em->getById($postId)->fetch();

        if (file_exists($post->teamALogo)) {
            $this->flashMessage("Obrazek byl odstranen.", "success");
            $this->em->delImg($postId, $post->teamALogo);
            unlink($post->teamALogo);
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
        $dir = 'upload/enemy/storage';
        $dirNew = 'upload/enemy/';
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
