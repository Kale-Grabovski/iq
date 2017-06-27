<?php

namespace AppBundle\Controller\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ResponseInterface
 *
 * @package AppBundle\Controller\Responses
 */
interface ResponseInterface
{
    /**
     * Returns custom Response object
     *
     * @param  array    $output
     * @return Response
     */
    public function output(array $output) : Response;
}
