<?php

namespace App\Controller;

use App\Manager\EmailManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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
     * @var EmailManager
     */
    private EmailManager $emailManager;

    /**
     * Class Constructor.
     *
     * @param EmailManager $emailManager The email manager instance.
     */
    public function __construct(EmailManager $emailManager)
    {
        $this->emailManager = $emailManager;
    }

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

    /**
     * Send test emails.
     *
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
    #[Route('/sendTestEmails', name: 'api_email', methods: ['GET'])]
    public function sendTestEmails(): JsonResponse
    {

        $this->emailManager->sendServiceDownEmail(['jakubkoholas@gmail.com', 'miracleebeast@gmail.com'], 'MySQL');

        return $this->json([
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'message' => 'Sentinel successfully sent test emails'
        ], Response::HTTP_OK);
    }
}
