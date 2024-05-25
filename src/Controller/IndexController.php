<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * IndexController
 *
 * This controller handles the index route of the Sentinel API.
 *
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * Handles the index route and returns a welcome message in JSON format.
     *
     * @return JsonResponse
     */
    #[Route('/', methods:['GET'], name: 'api_index')]
    public function index(): JsonResponse
    {
        return $this->json([
            'status' => 'success',
            'code' => JsonResponse::HTTP_OK,
            'message' => 'Welcome in Sentinel API!'
        ], JsonResponse::HTTP_OK);
    }
}
