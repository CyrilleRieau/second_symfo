<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ArticleChild
 *
 * @ORM\Table(name="article_child")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleChildRepository")
 */
class ArticleChild
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return ArticleChild
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
     /**
     * @ORM\OneToOne(targetEntity="FirstEntity")
     * @ORM\JoinColumn(name="firstentity_id", referencedColumnName="id")
     */
     private $firstentity_parent;

    /**
     * Set firstentityParent
     *
     * @param \AppBundle\Entity\FirstEntity $firstentityParent
     *
     * @return ArticleChild
     */
    public function setFirstentityParent(\AppBundle\Entity\FirstEntity $firstentityParent = null)
    {
        $this->firstentity_parent = $firstentityParent;

        return $this;
    }

    /**
     * Get firstentityParent
     *
     * @return \AppBundle\Entity\FirstEntity
     */
    public function getFirstentityParent()
    {
        return $this->firstentity_parent;
    }
}
