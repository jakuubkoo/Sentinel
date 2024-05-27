<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
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
    #[Route('/', name: 'api_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'message' => 'Sentinel successfully loaded'
        ], Response::HTTP_OK);
    }
}
