<?php
namespace MvaCrud\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Base Entity for crud operations
 *
 */
class BaseEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return BaseEntity
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }
    
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function fillWith($data){
        $this->id   = (isset($data['id']))      ? $data['id']   : null;
        $this->name = (isset($data['name']))    ? $data['name'] : $this->name;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
}