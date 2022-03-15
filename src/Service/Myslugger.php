<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

class Myslugger {

    private $symfonySlugger;
    private $toLower;
    // Cannot autowire service "App\Service\MySlugger": argument "$slugToLower" of method "__construct()" is type-hinted "bool", you should configure its value explicitly.

    public function __construct(SluggerInterface $slugger, bool $slugToLower)
    {
        $this->symfonySlugger = $slugger;
        $this->toLower = $slugToLower;
    }

    public function slugify(string $strToSlug): string
    {

        $slugifiedString = $this->symfonySlugger->slug($strToSlug);

        if ($this->toLower)
        {
            $slugifiedString = $slugifiedString->lower();
        }

        return $slugifiedString;
    }
}
