<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\DTO\TicketDto;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Service\RateLimitService;

final class SupportTicketController extends AbstractController
{
    public function __construct(
        private RateLimitService $rateLimitService
    ){}

    #[Route('/api/support/ticket', name: 'app_support_ticket', methods: ['POST'])]
    public function create(#[MapRequestPayload] TicketDto $ticketDto, HttpClientInterface $httpClient): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $this->rateLimitService->enforce($user->getEmail(), $request->getClientIp(), 'api');
        $this->rateLimitService->enforce($user->getEmail(), $request->getClientIp(), 'supportCreate');
        $profile = $user->getProfile();
        $reportedBy = sprintf('%s %s (Role: %s)', $profile->getMe()->getFirstName(), $profile->getMe()->getLastName(), $user->getRole());
        $ticketDto = $ticketDto->withReportedBy($reportedBy);

        $fileName = 'ticket_' . time() . '.json';
        $jsonPayload = json_encode($ticketDto, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $accessToken = $this->getAccessToken($httpClient);
        $response = $this->sendToDropBox($httpClient, $accessToken, $fileName, $jsonPayload);

        return $this->json(['message' => 'Support ticket created successfully'], 201);
    }

    private function getAccessToken(HttpClientInterface $httpClient): string {
        $response = $httpClient->request('POST', 'https://api.dropbox.com/oauth2/token', [
            'body' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->getParameter('dropbox.refresh_token'),
                'client_id' => $this->getParameter('dropbox.app_key'),
                'client_secret' => $this->getParameter('dropbox.app_secret'),
            ],
        ]);

        $data = $response->toArray();

        return $data['access_token'];
    }

    public function sendToDropBox(HttpClientInterface $httpClient, string $dropBoxToken, string $fileName, string $jsonPayload){
        $response = $httpClient->request('POST', 'https://content.dropboxapi.com/2/files/upload', [
                'headers' => [
                    'Content-Type' => 'application/octet-stream',
                    'Authorization' => 'Bearer ' . $dropBoxToken,
                    'Dropbox-API-Arg' => json_encode([
                        'path' => '/' . $fileName,
                        'mode' => 'add',
                        'autorename' => true,
                        'mute' => false,
                    ]),
                ],
                'body' => $jsonPayload,
            ]);

        $response->toArray();

        return $response;
    }
}
