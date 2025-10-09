<?php

namespace App\Controller;

use Random\RandomException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class LuckyController extends AbstractController
{
    /**
     * @throws RandomException
     */
     #[Route('/lucky/number')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}
