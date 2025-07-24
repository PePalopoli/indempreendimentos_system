<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

namespace Palopoli\PaloSystem\Controller\Security;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SecurityController.
 */
class SecurityController extends ContainerAware
{
    /**
     * Pagina inicial.
     */
    public function indexAction()
    {
        return $this->render('/security/index.twig');
    }

    /**
     * Pagina de login.
     */
    public function loginAction(Request $request)
    {
        $app = $this->getContainer();

        return $this->render('/security/login2.twig', array(
                'error' => $app['security.last_error']($request), // Exibir mensagem de erro
                'last_username' => $this->get('session')->get('_security.last_username'), // Preencher campo com último nome de usuário informado
            )
        );
    }
}
