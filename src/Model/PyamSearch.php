<?php

namespace App\Model;

class PyamSearch
{
    private ?\DateTime $date;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): void
    {
        $this->date = $date;
    }


}
