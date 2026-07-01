<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Arret;
use App\Entity\Trajet;
use App\Service\RouteCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrajetController extends AbstractController
{
    #[Route('/', name: 'app_depart', methods: ['GET', 'POST'])]
    public function depart(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $label = $request->request->get('depart_label', '');
            $lat = $request->request->get('depart_lat', '');
            $lon = $request->request->get('depart_lon', '');

            $request->getSession()->set('depart', [
                'label' => $label,
                'lat' => (float) $lat,
                'lon' => (float) $lon,
            ]);

            return $this->redirectToRoute('app_arrets');
        }

        $trajets = $em->getRepository(Trajet::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('trajet/depart.html.twig', [
            'trajets' => $trajets,
        ]);
    }

    #[Route('/arrets', name: 'app_arrets', methods: ['GET', 'POST'])]
    public function arrets(Request $request, RouteCalculator $calculator, EntityManagerInterface $em): Response
    {
        if (!$request->getSession()->has('depart')) {
            return $this->redirectToRoute('app_depart');
        }

        if ($request->isMethod('POST')) {
            $mode = $request->request->get('mode', 'loin');
            $labels = $request->request->all('arret_label');
            $lats = $request->request->all('arret_lat');
            $lons = $request->request->all('arret_lon');
            $noms = $request->request->all('arret_nom');

            $depart = $request->getSession()->get('depart');

            $arrets = [];
            foreach ($labels as $i => $label) {
                if ('' !== $label && '' !== $lats[$i] && '' !== $lons[$i]) {
                    $arrets[] = [
                        'label' => $label,
                        'nom' => $noms[$i] ?? '',
                        'lat' => (float) $lats[$i],
                        'lon' => (float) $lons[$i],
                        'distance' => $calculator->calculateDistance(
                            $depart['lat'],
                            $depart['lon'],
                            (float) $lats[$i],
                            (float) $lons[$i]
                        ),
                    ];
                }
            }

            // Tri selon le mode
            usort($arrets, fn ($a, $b) => $a['distance'] <=> $b['distance']);
            if ('loin' === $mode) {
                $final = array_pop($arrets);
                $arrets[] = $final;
            } else {
                $final = array_shift($arrets);
                $arrets[] = $final;
            }

            // Sauvegarde en BDD
            $trajet = new Trajet();
            $trajet->setTitre($depart['label'].' → '.end($arrets)['label']);
            $trajet->setMode($mode);
            $trajet->setDepartLabel($depart['label']);
            $trajet->setDepartLat($depart['lat']);
            $trajet->setDepartLon($depart['lon']);

            foreach ($arrets as $position => $arretData) {
                $arret = new Arret();
                $arret->setNom($arretData['nom']);
                $arret->setLabel($arretData['label']);
                $arret->setLat($arretData['lat']);
                $arret->setLon($arretData['lon']);
                $arret->setDistance($arretData['distance']);
                $arret->setPosition($position);
                $arret->setTrajet($trajet);
                $em->persist($arret);
            }

            $em->persist($trajet);
            $em->flush();

            $request->getSession()->set('arrets', $arrets);
            $request->getSession()->set('mode', $mode);
            $request->getSession()->set('trajet_id', $trajet->getId());

            return $this->redirectToRoute('app_feuille_de_route');
        }

        return $this->render('trajet/arrets.html.twig', [
            'depart' => $request->getSession()->get('depart'),
        ]);
    }

    #[Route('/feuille-de-route', name: 'app_feuille_de_route', methods: ['GET'])]
    public function feuilleDeRoute(Request $request): Response
    {
        if (!$request->getSession()->has('depart')) {
            return $this->redirectToRoute('app_depart');
        }

        return $this->render('trajet/feuille_de_route.html.twig', [
            'depart' => $request->getSession()->get('depart'),
            'arrets' => $request->getSession()->get('arrets', []),
            'mode' => $request->getSession()->get('mode', 'loin'),
        ]);
    }

    #[Route('/trajet/{id}', name: 'app_trajet_voir', methods: ['GET'])]
    public function voirTrajet(Trajet $trajet, Request $request): Response
    {
        $arrets = $trajet->getArrets()->toArray();
        usort($arrets, fn ($a, $b) => $a->getPosition() <=> $b->getPosition());

        $arretsData = array_map(fn ($a) => [
            'label' => $a->getLabel(),
            'nom' => $a->getNom(),
            'lat' => $a->getLat(),
            'lon' => $a->getLon(),
            'distance' => $a->getDistance(),
        ], $arrets);

        return $this->render('trajet/feuille_de_route.html.twig', [
            'depart' => ['label' => $trajet->getDepartLabel()],
            'arrets' => $arretsData,
            'mode' => $trajet->getMode(),
        ]);
    }

    #[Route('/trajet/{id}/supprimer', name: 'app_trajet_supprimer', methods: ['POST'])]
    public function supprimerTrajet(Trajet $trajet, EntityManagerInterface $em): Response
    {
        $em->remove($trajet);
        $em->flush();

        return $this->redirectToRoute('app_depart');
    }
}
