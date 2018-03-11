<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genus;
use AppBundle\Entity\GenusNote;
use Doctrine\ORM\EntityManager;
use AppBundle\Repository\GenusRepository;
use AppBundle\Repository\GenusNoteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GenusController extends Controller
{
    /**
     * @Route("/genus/new", name="new_genus")
     */
    public function newAction()
    {
        $genus = new Genus();
        $genus->setName('Octopus'.rand(1, 100));
        $genus->setSpeciesCount(rand(100, 99999));
        $genus->setSubFamily('Octopediane');

        $genusNote = new GenusNote();
        $genusNote->setUsername('AquaWeaver');
        $genusNote->setUserAvatarFileName('ryan.jpeg');
        $genusNote->setNote('I counted 8 legs but I saw 65 legs for some reason. This is crazy!');
        $genusNote->setCreatedAt(new \DateTime('-1 month'));
        $genusNote->setGenus($genus);

        $em = $this->getDoctrine()->getManager();
        $em->persist($genus);
        $em->persist($genusNote);
        $em->flush();

        return new Response('<html> <body>genus created</body></html>');
    }
    // route action methods ordering do matter especially when there is a wildcard and card lead to unexpected behaviour

    /**
     * @Route("/genus")
     */
    public function listAction()
    {
        /** @var GenusRepository $em */
        $em = $this->getDoctrine()->getManager();
        $genuses = $em->getRepository('AppBundle:Genus')->findAllPublishedOrderByRecentlyActive();
        //dump($em->getRepository('AppBundle:Genus'));
        return $this->render('genus/list.html.twig', [
            'genuses' => $genuses,
        ]);
    }
    /**
     * @Route("/genus/{genusName}", name="genus_show")
     */
    public function showAction($genusName)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Genus $genus */
        $genus = $em->getRepository('AppBundle:Genus')->findOneBy(['name' => $genusName]);

        if(!$genus) {
            throw $this->createNotFountException('no genus found');
        }
        /*
        $cache = $this->get('doctrine_cache.providers.my_markdown_cache');
        $key = md5($funFact);
        if ($cache->contains($key)) {
            $funFact = $cache->fetch($key);
        } else {
            sleep(1); // fake how slow this could be
            $funFact = $this->get('markdown.parser')
                ->transform($funFact);
            $cache->save($key, $funFact);
        }
        */
        /*
        $recentNote = $genus->getNotes()->filter(function (GenusNote $note) {
            return $note->getCreatedAt() > new \DateTime('-3 months');
        });
        */
        $recentNote = $em->getRepository('AppBundle:GenusNote')->findAllRecentNotesForGenus($genus);

        $this->get('logger')
            ->info('Showing genus: '.$genusName);

        return $this->render('genus/show.html.twig', array(
            'genus' => $genus,
            'recentNoteCount' => count($recentNote)
        ));
    }

    /**
     * @Route("/genus/{name}/notes", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction(Genus $genus) // when you typehint an argument with entity, symfony automatically query for it  - works so long as the wild card has same name as a property on genus
    {
        $notes = [];

        foreach ($genus->getNotes() as $note) {
            $notes[] = [
                'id' => $note->getId(),
                'username' => $note->getUsername(),
                'avatarUri' => '/images/'. $note->getUserAvatarFileName(),
                'note' => $note->getNote(),
                'date' => $note->getCreatedAt()->format('Y-m-d')
            ];
        }
        $data = [
            'notes' => $notes
        ];

        return new JsonResponse($data);
    }
}
