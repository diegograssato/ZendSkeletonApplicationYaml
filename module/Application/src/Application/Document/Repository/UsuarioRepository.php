<?php

namespace Application\Document\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class UsuarioRepository extends DocumentRepository
{

    public function findByEmailAndPassword($email, $password)
    {
        $user = $this->findOneByEmail($email);
        if($user)
        {
            $hashSenha = $user->encryptPassword($password);
           // if(($hashSenha == $user->getPassword()) &&  $user->getActive())
            if($hashSenha == $user->getPassword())
                return $user;
            else
                return false;
        }
        else
            return false;
    }

}
