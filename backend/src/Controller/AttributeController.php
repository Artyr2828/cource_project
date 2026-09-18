<?php

namespace App\Controller;

use App\Repository\AttributesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AttributeController extends AbstractController
{
    public function __construct(private AttributesRepository $attributesRepository){}

    #[Route('/api/attribute', name: 'app_attribute')]
    public function get(): Response
    {
        $attributes = $this->attributesRepository->findAll();
        return $this->json($attributes);
    }
}
