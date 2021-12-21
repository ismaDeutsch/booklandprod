<?php


namespace App\Entity;


class AuteurSearch
{

    /**
     * @var int|null
     */
    private $minLivre;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $sexe;

    /**
     * @var string|null
     */
    private $nationality;

    private $year;

    /**
     * @return int|null
     */
    public function getMinLivre(): ?int
    {
        return $this->minLivre;
    }

    /**
     * @param int|null $minLivre
     * @return AuteurSearch
     */
    public function setMinLivre(int $minLivre): AuteurSearch
    {
        $this->minLivre = $minLivre;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return AuteurSearch
     */
    public function setName(string $name): AuteurSearch
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    /**
     * @param string|null $sexe
     * @return AuteurSearch
     */
    public function setSexe(string $sexe): AuteurSearch
    {
        $this->sexe = $sexe;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    /**
     * @param string|null $nationality
     * @return AuteurSearch
     */
    public function setNationality(string $nationality): AuteurSearch
    {
        $this->nationality = $nationality;
        return $this;
    }


    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param \DateTimeInterface|null $year
     * @return AuteurSearch
     */
    public function setYear($year): AuteurSearch
    {
        $this->year = $year;
        return $this;
    }

}