<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\DTO\SalesforceDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SalesforceController extends AbstractController
{
    public function __construct(private HttpClientInterface $httpClient, private EntityManagerInterface $entityManager){}

    #[Route('/api/salesforce', name: 'app_salesforce', methods: ['POST'])]
    public function create(#[MapRequestPayload] SalesforceDto $dto): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $profile = $user->getProfile();

        $dto = $dto->withBasicData(
            $profile->getMe()->getFirstName(),
            $profile->getMe()->getLastName(),
            $user->getEmail()
        );

        $recordIds = $this->sendToSalesforce($dto);
        $user->setSalesforceAccountId($recordIds[0]);
        $user->setSalesforceContactId($recordIds[1]);   
        $this->entityManager->flush();
        return $this->json(['message' => 'Salesforce record created successfully', 'isSyncedWithSalesforce' => true]);
    }

    #[Route('/api/salesforce', name: 'app_salesforce_update', methods: ['PATCH'])]
    public function update(#[MapRequestPayload] SalesforceDto $dto): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $profile = $user->getProfile();

        $dto = $dto->withBasicData(
            $profile->getMe()->getFirstName(),
            $profile->getMe()->getLastName(),
            $user->getEmail()
        );

        $this->updateToSalesforce($dto, $user->getSalesforceAccountId(), $user->getSalesforceContactId());
        return $this->json(['message' => 'Salesforce record updated successfully', 'isSyncedWithSalesforce' => true]);
    }

    public function sendToSalesforce(SalesforceDto $dto): array {
     /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!is_null($user->getSalesforceAccountId()) || !is_null($user->getSalesforceContactId())) {
            throw new \InvalidArgumentException('Salesforce Account ID and Contact ID must be null for creation');
        }

        $account = [
            'Name' => $dto->companyName
        ];

        $dataSalesforse = $this->getTokenAndInstanceUrl();
        $instanceUrl = $dataSalesforse['instance_url'];
        $accessToken = $dataSalesforse['access_token'];

        $url = sprintf('%s/services/data/v60.0/sobjects/Account', $instanceUrl);


        $responseAccount = $this->httpClient->request('POST', $url, [
            'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type'  => 'application/json',
            ],
            'json' => $account
        ]);

        $accountResponse = $responseAccount->toArray();
        $accountId = $accountResponse['id'];

        $contact = [
            'AccountId' => $accountId, 
            'FirstName' => $dto->firstName,
            'LastName' => $dto->lastName,
            'Email' => $dto->email,
            'Title' => $dto->position,
            'Phone' => $dto->phone,
        ];

        

        $url = sprintf('%s/services/data/v60.0/sobjects/Contact', $instanceUrl);

        $responseContact = $this->httpClient->request('POST', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
            ],
            'json' => $contact
        ]);
        
        $responseContact->toArray();
        $contactId = $responseContact->toArray()['id'];
        return [$accountId, $contactId];
    }

    function updateToSalesforce(SalesforceDto $dto, ?string $accountId, ?string $contactId): void {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (is_null($accountId) || is_null($contactId)) {
            throw new \InvalidArgumentException('Salesforce Account ID and Contact ID must not be null for update.');
        }
        $dataSalesforse = $this->getTokenAndInstanceUrl();
        $instanceUrl = $dataSalesforse['instance_url'];
        $accessToken = $dataSalesforse['access_token'];

        // Update Account
        $accountUpdateData = [
            'Name' => $dto->companyName
        ];

        $urlAccount = sprintf('%s/services/data/v60.0/sobjects/Account/%s', $instanceUrl, $accountId);

        $respAccount = $this->httpClient->request('PATCH', $urlAccount, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
            ],
            'json' => $accountUpdateData
        ]);

        if ($respAccount->getStatusCode() === 404) {
            $user->setSalesforceAccountId(null);
            $user->setSalesforceContactId(null);
            $this->entityManager->flush();
            throw new NotFoundHttpException('Salesforce account or contact not found. Synchronization has been reset. Please try again after closing the modal window');
        }


        if ($respAccount->getStatusCode() !== 204) {
            $error = $respAccount->toArray(false);
            throw new \RuntimeException('error of update Account in Salesforce: ' . json_encode($error));
        }

        // Update Contact
        $contactUpdateData = [
            'FirstName' => $dto->firstName,
            'LastName' => $dto->lastName,
            'Email' => $dto->email,
            'Title' => $dto->position,
            'Phone' => $dto->phone,
        ];

        $urlContact = sprintf('%s/services/data/v60.0/sobjects/Contact/%s', $instanceUrl, $contactId);

        $respContact = $this->httpClient->request('PATCH', $urlContact, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
            ],
            'json' => $contactUpdateData
        ]);

        if ($respAccount->getStatusCode() === 404) {
            $user->setSalesforceAccountId(null);
            $user->setSalesforceContactId(null);
            $this->entityManager->flush();
            throw new NotFoundHttpException('Salesforce account or contact not found. Synchronization has been reset. Please try again after closing the modal window');
        } else {
            throw new \RuntimeException("Error in salesforce");
        }
        
    }

    public function getTokenAndInstanceUrl(): array {
        $clientId = $this->getParameter('salesforce.client_id');
        $clientSecret = $this->getParameter('salesforce.client_secret');
        $loginUrl = rtrim($this->getParameter('salesforce.login_url'), '/');

        $authResponse = $this->httpClient->request('POST', $loginUrl . '/services/oauth2/token', [
            'body' => [
                'grant_type'    => 'client_credentials',
                'client_id'     => $clientId,
                'client_secret' => $clientSecret
            ]
        ]);

        $authData = $authResponse->toArray();

        $instanceUrl = $authData['instance_url'];
        $accessToken = $authData['access_token'];

        return [
            'instance_url' => $instanceUrl,
            'access_token' => $accessToken
        ];

    }
}