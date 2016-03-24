<?php 
namespace App\Model;

abstract class TableManager extends \Nette\Object {
    
    private $connection;
    protected $tableName;
    
    public function __construct(\Nette\Database\Context $connection) {
	$this->connection = $connection;
    }
    
    protected function getTable(){
	//preg_match('#(\w+)Repository$#', get_class($this), $m);
        return $this->connection->table($this->tableName);
    }
    
    public function findAll(){
	return $this->getTable();
    }

    public function getCount(){
    return $this->getTable()->count();
    }   

    public function findBy(array $by){
	return $this->getTable()->where($by);
    }

    public function findAllVisible(){
    return $this->getTable()->where('active = ?', 1);
    }   
}