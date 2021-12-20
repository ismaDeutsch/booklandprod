<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;


class LivreSearch
{
    /**
     * @var string|null
     */
    private $title;

    private $publication_date;
    private $publication_date_end;

    /**
     * @var int|null
     */
    private $numberOfPage;

    /**
     * @var ArrayCollection
     */
    private $authors;

    /**
     * @var ArrayCollection
     */
    private $genres;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->genres = new ArrayCollection();
    }

    /**
     * @var int|null
     */
    private $noteMin;

    /**
     * @var int|null
     */
    private $noteMax;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return LivreSearch
     */
    public function setTitle(string $title): LivreSearch
    {
        $this->title = $title;
        return $this;
    }


    public function getPublicationDate()
    {
        return $this->publication_date;
    }

    /**
     * @param \DateTimeInterface|null $publication_date
     * @return LivreSearch
     */
    public function setPublicationDate($publication_date): LivreSearch
    {
        $this->publication_date = $publication_date;
        return $this;
    }


    public function getPublicationDateEnd()
    {
        return $this->publication_date_end;
    }

    /**
     * @param mixed $publication_date_end
     * @return LivreSearch
     */
    public function setPublicationDateEnd($publication_date_end)
    {
        $this->publication_date_end = $publication_date_end;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumberOfPage(): ?int
    {
        return $this->numberOfPage;
    }

    /**
     * @param int|null $numberOfPage
     * @return LivreSearch
     */
    public function setNumberOfPage(int $numberOfPage): LivreSearch
    {
        $this->numberOfPage = $numberOfPage;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAuthors(): ArrayCollection
    {
        return $this->authors;
    }

    /**
     * @param ArrayCollection $authors
     * @return LivreSearch
     */
    public function setAuthors(ArrayCollection $authors): LivreSearch
    {
        $this->authors = $authors;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getGenres(): ArrayCollection
    {
        return $this->genres;
    }

    /**
     * @param ArrayCollection $genres
     * @return LivreSearch
     */
    public function setGenres(ArrayCollection $genres): LivreSearch
    {
        $this->genres = $genres;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNoteMin(): ?int
    {
        return $this->noteMin;
    }

    /**
     * @param int|null $noteMin
     * @return LivreSearch
     */
    public function setNoteMin(int $noteMin): LivreSearch
    {
        $this->noteMin = $noteMin;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNoteMax(): ?int
    {
        return $this->noteMax;
    }

    /**
     * @param int|null $noteMax
     * @return LivreSearch
     */
    public function setNoteMax(?int $noteMax): LivreSearch
    {
        $this->noteMax = $noteMax;
        return $this;
    }

}