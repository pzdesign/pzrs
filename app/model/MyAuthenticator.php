<?php
use Nette\Security as NS;

class MyAuthenticator extends Nette\Object implements NS\IAuthenticator
{
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        $row = $this->database->table('pzrs_users')
            ->where('username', $username)->fetch();

        if (!$row) {
            throw new NS\AuthenticationException('User not found.');
        }

        if (!NS\Passwords::verify($password, $row->password)) {
            throw new NS\AuthenticationException('Invalid password.');
        }

        return new NS\Identity($row->id, $row->role, array('username' => $row->username));
    }
}
