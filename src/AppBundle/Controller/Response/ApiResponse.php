<?php

namespace AppBundle\Controller\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse extends JsonResponse implements ResponseInterface
{
    /**
     * Returns custom Response object
     *
     * @param  array    $output
     * @return Response
     */
    public function output(array $output) : Response
    {
        $json = json_encode($output, $this->encodingOptions);
        return new Response($json);
    }
}
