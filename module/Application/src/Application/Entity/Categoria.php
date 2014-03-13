<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation as Form;

/**
 * Priority
 *
 * @ORM\Table(name="categoria")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Entity\CategoriaRepository")
 * @ORM\HasLifecycleCallbacks
 * @Form\Name("categoria")
 * @Form\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class Categoria
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Form\Exclude()
     * @var $id
     */
    protected $id;

    /**
     * @ORM\Column(name="nome", type="string", length=45, nullable=false)
     * @Form\Required(true)
     * @Form\Type("Zend\Form\Element\Text")
     * @Form\Filter({"name":"StringTrim"})
     * @Form\Filter({"name":"StripTags"})
     * @Form\Attributes({"class":"input-block-level", "maxlength":"45", "placeholder":"Entre com o nome"})
     * @Form\Options({"label":"Nome:"})
     * @var $nome
     */
    protected $nome;

    /**
     * @ORM\Column(name="data_cadastro", type="datetime", nullable=false)
     * @Form\Exclude()
     * @var $data_cadastro
     */
    protected $data_cadastro;

    /**
     * @ORM\Column(name="ativo", type="boolean", nullable=false)
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Checkbox")
     * @Form\Attributes({"class":"input-block-level", "maxlength":"45", "placeholder":"Entre com o nome"})
     * @Form\Options({"label":"Nome:"})
     * @var $ativo
     */
    protected $ativo = true;

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $nome
     * @return $this
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @ORM\PrePersist
     * @param $data_cadastro
     * @return $this
     */
    public function setDataCadastro($data_cadastro)
    {
        if (!$data_cadastro instanceof \DateTime){
            $this->data_cadastro = new \DateTime('now');
        }else{
            $this->data_cadastro = $data_cadastro;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * @param mixed $ativo
     * @return $this
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }
} 