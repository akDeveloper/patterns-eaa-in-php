<?php

declare(strict_types = 1);

namespace WebPresentation\FrontController;

use Model\Artist;

class ArtistHelper
{
    private $artist;

    public function __construct(Artist $artist)
    {
        $this->artist = $artist;
    }

    public function getArtistName(): string
    {
        return $this->artist->getName();
    }
}
