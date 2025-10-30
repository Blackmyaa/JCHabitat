<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/legal')]
class LegalController extends AbstractController
{
    public function __construct(private ParameterBagInterface $params)
    {
    }

    #[Route('/mentions-legales', name: 'app_mentions_legales')]
    public function mentionsLegales(): Response
    {
        return $this->render('legal/mentions_legales.html.twig', [
            'company_name' => $this->params->get('company_name'),
            'company_email' => $this->params->get('company_email'),
            'company_legal_form'=>$this->params->get('company_legal_form'),
            'company_phone' => $this->params->get('company_phone'),
            'company_address' => $this->params->get('company_address'),
            'company_siret' => $this->params->get('company_siret'),
            'company_responsable' => $this->params->get('company_responsable'),
            'host_name' => $this->params->get('host_name'),
            'host_address' => $this->params->get('host_address'),
            'host_phone' => $this->params->get('host_phone'),
            'insurance_company' => $this->params->get('insurance_company'),
            'insurance_contract' => $this->params->get('insurance_contract'),
            'insurance_zone' => $this->params->get('insurance_zone'),
        ]);
    }

    #[Route('/politique-confidentialite', name: 'app_politique_confidentialite')]
    public function politiqueConfidentialite(): Response
    {
        return $this->render('legal/politique_confidentialite.html.twig', [
            'company_name' => $this->params->get('company_name'),
            'company_email' => $this->params->get('company_email'),
        ]);
    }

    #[Route('/conditions-generales', name: 'app_conditions_generales')]
    public function conditionsGenerales(): Response
    {
        return $this->render('legal/conditions_generales.html.twig', [
            'company_name' => $this->params->get('company_name'),
        ]);
    }
}