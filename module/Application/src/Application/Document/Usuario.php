<?php
namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zend\Stdlib\Hydrator,
    Zend\Math\Rand,
    Zend\Crypt\Key\Derivation\Pbkdf2;
use DTUXBase\Document\AbstractDocument as AbstractDocument;

/**
 * Usuario
 *
 * @ODM\Document(
 *     collection="Usuario",
 *     repositoryClass="Application\Document\Repository\UsuarioRepository"
 * ),
 * @ODM\HasLifecycleCallbacks
 */
class Usuario
{

     /** @ODM\Id @ODM\Index */
    protected $id;

    /** @ODM\Increment */
    protected $changes = 0;

    /** @ODM\String @ODM\UniqueIndex(order="asc") */
    protected $nome;

    /** @ODM\String @ODM\UniqueIndex(order="asc") */
    protected $email;

    /** @ODM\Field(type="string")*/
    protected $password;

    /** @ODM\Field(type="string") */
    protected $salt;

    /** @ODM\Field(type="string") */
    protected $active;

    /** @ODM\Field(type="string") */
    protected $activationKey;

    /** @ODM\Date */
    protected $createdAt;

    /** @ODM\Date  */
    protected $updatedAt;


    public function __construct(array $options = array())
    {
        (new Hydrator\ClassMethods)->hydrate($options,$this);

    }

    public function __toString()
    {
        return $this->nome ? $this->nome : $this->username;
    }

    public function toArray()
    {
        return (new Hydrator\ClassMethods())->extract($this);
    }

    public function toJson($debug = true)
    {
        $json = \Zend\Json\Json::encode((new Hydrator\ClassMethods())->extract($this), true);
        if ($debug)
            $json = \Zend\Json\Json::prettyPrint($json, array("indent" => " # "));
        return $json;
    }

    /**
     * @ODM\PreUpdate
     */
    public function preUpdate()
    {
        $this->changes++;
        $this->updatedAt = new \MongoDate();
    }

    /**
     * @ODM\PreFlush
     */
    public function preFlusht()
    {
        ($this->createdAt)? null :$this->createdAt = new \MongoDate();
        $this->updatedAt = new \MongoDate();
    }

    public function setPassword($password) {
       $this->password = $this->encryptPassword($password);
       return $this;
    }

    public function encryptPassword($password)
    {
        if(!is_string($this->salt)){
            $this->salt = md5(Rand::getBytes(strlen($this->getEmail().$this->salt), true));
            $this->activationKey = md5($this->getEmail().$this->salt);
        }
        return md5(Pbkdf2::calc('sha256', $password, $this->salt, 10000, strlen($password*2)));
    }

    /**
     * Gets the value of updatedAt.
     *
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Sets the value of updatedAt.
     *
     * @param mixed $updatedAt the updated at
     *
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param mixed $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of changes.
     *
     * @return mixed
     */
    public function getChanges()
    {
        return $this->changes;
    }

    /**
     * Sets the value of changes.
     *
     * @param mixed $changes the changes
     *
     * @return self
     */
    public function setChanges($changes)
    {
        $this->changes = $changes;

        return $this;
    }

    /**
     * Gets the value of nome.
     *
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Sets the value of nome.
     *
     * @param mixed $nome the nome
     *
     * @return self
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Gets the value of email.
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the value of email.
     *
     * @param mixed $email the email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Gets the value of password.
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Gets the value of salt.
     *
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Sets the value of salt.
     *
     * @param mixed $salt the salt
     *
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Gets the value of active.
     *
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Sets the value of active.
     *
     * @param mixed $active the active
     *
     * @return self
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Gets the value of activationKey.
     *
     * @return mixed
     */
    public function getActivationKey()
    {
        return $this->activationKey;
    }

    /**
     * Sets the value of activationKey.
     *
     * @param mixed $activationKey the activation key
     *
     * @return self
     */
    public function setActivationKey($activationKey)
    {
        $this->activationKey = $activationKey;

        return $this;
    }

    /**
     * Gets the value of createdAt.
     *
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets the value of createdAt.
     *
     * @param mixed $createdAt the created at
     *
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

