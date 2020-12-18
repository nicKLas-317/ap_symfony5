<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $content = "Accès interdit, vous devez avoir les privilèges " . $accessDeniedException->getAttributes()[0] . " <a href='/'>Retourner à l'accueil</a>";

        return new Response($content, 403);
    }
}
