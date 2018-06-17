<?php

declare(strict_types = 1);

namespace WebPresentation\FrontController;

class UnknownCommand extends FrontCommand
{
    public function process(): void
    {
        $this->forward("/unknown.php");
    }
}
