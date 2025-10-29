<?php

namespace App\Controller\Admin;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Devis;
use App\Entity\DevisLigne;
use App\Entity\DemandeDeDevis;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DemandeDeDevisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/devis')]
class AdminDevisController extends AbstractController
{
    #[Route('/create/{demandeId}', name: 'admin_devis_create')]
    public function create(
        int $demandeId,
        DemandeDeDevisRepository $demandeRepo,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $demande = $demandeRepo->find($demandeId);
        if (!$demande) {
            throw $this->createNotFoundException('Demande de devis introuvable.');
        }

        if (!$demande->getClient()) {
            $this->addFlash('danger', 'Aucun client associé à cette demande de devis.');
            return $this->redirectToRoute('admin_notifications_index');
        }

        if ($request->isMethod('POST')) {
            $devis = new Devis();
            $devis->setDemande($demande);
            $devis->setClient($demande->getClient());
            $devis->setAdministrateur($this->getUser());
            $devis->setDateCreation(new \DateTime());
            $devis->setNumeroDevis('DEV-' . date('YmdHis'));
            $devis->setStatut('en_cours');


            $linesData = $request->request->all('lines');
            $total = 0;

            if (is_array($linesData)) {
                foreach ($linesData as $line) {
                    if (empty($line['description']) || !isset($line['unitPrice'])) continue;

                    $quantity = (float) ($line['quantity'] ?? 1);
                    $unitPrice = (float) ($line['unitPrice'] ?? 0);
                    $subtotal = $quantity * $unitPrice;
                    $total += $subtotal;

                    // ✅ Création d'une ligne de devis
                    $ligne = new DevisLigne();
                    $ligne->setTitle($line['title']);
                    $ligne->setDescription($line['description']);
                    $ligne->setQuantity($quantity);
                    $ligne->setUnitPrice($unitPrice);
                    $ligne->setDevis($devis);

                    $em->persist($ligne);
                    $devis->addLigne($ligne);
                }
            }
            $devis->setMontantTotal($total);

            $em->persist($devis);
            $em->flush();

            $this->addFlash('success', 'Devis créé avec succès.');
            return $this->redirectToRoute('admin_devis_view', ['id' => $devis->getId()]);
        }

        return $this->render('admin/devis/createDevis.html.twig', [
            'demande' => $demande,
            'client' => $demande->getClient(),
        ]);
    }
    #[Route('/view/{id}', name: 'admin_devis_view', methods: ['GET'])]
    public function view(int $id, EntityManagerInterface $em ): Response 
    {
        $devis = $em->getRepository(Devis::class)->find($id);

        if (!$devis) {
            throw $this->createNotFoundException('Devis introuvable.');
        }

        $demande = $devis->getDemande();
        $client = $devis->getClient();

        return $this->render('Admin/devis/view.html.twig', [
            'devis' => $devis,
            'demande' => $demande,
            'client' => $client,
        ]);
    }


    #[Route('/Pdf/{id}', name: 'admin_devis_pdf', methods: ['GET'])]
    public function generatePdf(DevisRepository $devisRepo, int $id): Response
    {
        $devis = $devisRepo->find($id);
        if (!$devis) {
            throw $this->createNotFoundException('Devis introuvable.');
        }

        // ✅ Options Dompdf
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans'); // Compatible UTF-8
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        // ✅ Conversion du logo en Base64
        $projectDir = $this->getParameter('kernel.project_dir');
        $logoPath = $projectDir . '/public/images/Logo-JCHabitat.JPG';
        
        // Pour Dompdf : convertir les antislashs en slashs
        $logoPath = str_replace('\\', '/', $logoPath);

        if (file_exists($logoPath)) {
            $imageData = base64_encode(file_get_contents($logoPath));
            $base64Logo = 'data:image/jpeg;base64,' . $imageData;
        } else {
            $base64Logo = null; // ou une image de secours
        }

        // ✅ Rendu du HTML depuis Twig
        $html = $this->renderView('Admin/devis/devisPdf.html.twig', [
        'devis' => $devis,
        'logoPath' => $logoPath, // 🔹 On passe le chemin au template
        'base64Logo' => $base64Logo,
        ]);

        // ✅ Génération du PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont('DejaVu Sans', 'normal');

        
        $dompdf->render();
        // ✅ Texte fixe au dessus de la pagination
        $canvas->page_text(
            50,
            810,
            "Entreprise individuelle au capital de 0 euros — NAF 8121Z — N° Siret : 53363513200020",
            $font,
            8,
            [0, 0, 0]
        );

        // ✅ Pagination centrée tout en bas
        $canvas->page_text(
            270, // position X
            825, // position Y (ajuste selon ta marge bas)
            "Page {PAGE_NUM} sur {PAGE_COUNT}",
            $font,
            9,
            [0, 0, 0] // couleur noire
        );

        // ✅ Téléchargement du PDF
        return new Response($dompdf->stream(
            'Devis-' . $devis->getNumeroDevis() . '.pdf',
            ['Attachment' => true]
        ));
    }

    #[Route('/send/{id}', name: 'admin_devis_send', methods: ['GET'])]
    public function sendToClient(
        int $id,
        DevisRepository $devisRepo,
        \Symfony\Component\Mailer\MailerInterface $mailer
    ): Response {
        $devis = $devisRepo->find($id);
        if (!$devis) {
            throw $this->createNotFoundException('Devis introuvable.');
        }

        $client = $devis->getClient();
        $email = $client->getEmail();
        $nomPrenom = $client->getNom() . ' ' . $client->getPrenom();

        // Génération du PDF
        $pdfContent = $this->generatePdfContent($devis);

        // Création de l'email
        $emailMessage = (new \Symfony\Component\Mime\Email())
            ->from('contact@jchabitat.fr')
            ->to($email)
            ->subject('Votre devis #' . $devis->getNumeroDevis())
            ->html($this->renderView('admin/devis/emailDevis.html.twig', [
                'devis' => $devis,
                'client' => $client,
            ]))
            ->attach($pdfContent, 'Devis-' . $devis->getNumeroDevis() . '.pdf', 'application/pdf');

        $mailer->send($emailMessage);

        $this->addFlash('success', 'Le devis a été envoyé au client.');
        return $this->redirectToRoute('admin_devis_view', ['id' => $devis->getId()]);
    }

    private function generatePdfContent(Devis $devis): string
    {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $projectDir = $this->getParameter('kernel.project_dir');
        $logoPath = $projectDir . '/public/images/Logo-JCHabitat.JPG';
        $logoPath = str_replace('\\', '/', $logoPath);

        $base64Logo = file_exists($logoPath)
            ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $html = $this->renderView('Admin/devis/devisPdf.html.twig', [
            'devis' => $devis,
            'base64Logo' => $base64Logo,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }


}
