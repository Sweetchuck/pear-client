<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient;

use DOMDocument;

interface ParserInterface
{
    public function parse(DOMDocument $doc): array;
}
