<?php

declare(strict_types = 1);

namespace WebPresentation\FrontController;

use Model\Artist;

class ArtistCommand extends FrontCommand
{
    public function process(): void
    {
        $artist = Artist::findNamed($this->request->getQueryParams()['name']);
        $this->request = $this->request->withAttribute("helper", new ArtistHelper($artist));
        $this->forward(__DIR__ . "/templates/artist.php");
    }
}
