<?php 
namespace App\Model;

class PlayersManager extends TableManager
{

    private $connection;
    protected $tableName = 'pzrs_players';

    public function getPostsLimited($limit, $offset)
    {
        return $this->findAll()->limit($limit, $offset);
    }


    public function getAllPosts()
    {
        return $this->findAll();
    }

    public function getByName($name)
    {
        return $this->findAll()->fetchPairs($name);
    }


    public function getPostsCount()
    {
        return $this->findAll()->count();
    }

    public function getVisiblePostsCount()
    {
        return $this->findAllVisible()->count();
    }

    public function getById($id)
    {
        return $this->findBy(array('id' => $id));
    }

    public function findByName($name)
    {
        return $this->findBy(array('teamA' => $name));
    }

/*
    public function editPost($id, $title, $teaser,$active){
    $this->findBy(array('id' => $id))->update(array("title" => $title, 'teaser' => $teaser, 'active' => $active));
    }
*/
    public function editPost($id, $teamA, $teamALogo, $created_at)
    {
        $this->findBy(array('id' => $id))->update(array(
            'teamA'     => $teamA,
            'teamALogo'     => $teamALogo,
            'created_at' => $created_at));
    }

    public function delImg($id, $img)
    {
        $this->findBy(array('id' => $id))->update(array('playerphoto' => null));
    }

    public function updateImg($id, $old, $new)
    {
        $this->findBy(array('id' => $id))->update(array('teamALogo' => $new));
    }

    public function setActive($id, $active)
    {
        $this->findBy(array("id" => $id))->update(array("active" => $active));
    }
    
    public function addItem($nick, $fn, $ln, $photo, $mouse, $keyboard, $headphones, $cpu, $gpu, $sensitivity, $resolution, $facebook, $twitch, $twitter, $steam)
    {
        $query = $this->findAll()->insert(array(
            'nickname'     => $nick,
            'firstname'     => $fn,
            'lastname'     => $ln,
            'mouse'     => $mouse,
            'keyboard'     => $keyboard,
            'headphones'     => $headphones,
            'cpu'     => $cpu,
            'gpu'     => $gpu,
            'sensitivity'     => $sensitivity,
            'resolution'     => $resolution,
            'facebook'     => $facebook,
            'twitch'     => $twitch,
            'twitter'     => $twitter,
            'steam'     => $steam,
            'playerphoto'     => $photo,
            'active'     => 1));
    }
    public function justAdd()
    {
     $query = $this->findAll()->insert(array(
            'nickname'     => $nick,
            'fisrtname'     => $fn,
            'lastname'     => $ln,
            'playerphoto'     => $photo,
            'active'     => 1,
            'res'     => 1,
            'created_at' => $created_at));   
    }

    public function removeItem($id)
    {
        $this->findBy(array('id' => $id))->delete();
    }
}
