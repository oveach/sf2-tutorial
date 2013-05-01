<?php
namespace Tuto\AlbumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DMS\Filter\Rules as Filter;

/**
 * @ORM\Entity
 * @ORM\Table(name="album")
 */
class Album
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * @ORM\Column(name="artist", type="string", length=100, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="100")
     * @Filter\Trim
     * @Filter\StripTags
     */
    protected $artist;
    
    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="100")
     * @Filter\Trim
     * @Filter\StripTags
     */
    protected $title;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set artist
     *
     * @param string $artist
     * @return Album
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
    
        return $this;
    }

    /**
     * Get artist
     *
     * @return string 
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Album
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
}