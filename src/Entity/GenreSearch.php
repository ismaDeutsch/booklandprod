<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class GenreSearch
{

    /**
     * @var ArrayCollection
     */
    private $genre;

    public function __construct()
    {
        $this->genre = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getGenre(): ArrayCollection
    {
        return $this->genre;
    }

    /**
     * @param ArrayCollection $genre
     * @return GenreSearch
     */
    public function setGenre(ArrayCollection $genre): GenreSearch
    {
        $this->genre = $genre;
        return $this;
    }


}