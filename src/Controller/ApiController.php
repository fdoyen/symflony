<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiController extends AbstractController
{
	protected $base_url = "http://51.255.160.47:8181/";
	protected $region = "euw1/";
	protected $who = "passerelle/";
	protected $endpoint = "getHistoryMatchList/";
    /**
     * @Route("/fetchapi", name="fetchapi")
     */
    public function fetchApi(EntityManagerInterface $entityManager)
    {
    	// Appeler cette route une seule fois sinon multiple doublons en BDD
        $username = 'azerty';

        $url = file_get_contents($this->base_url.$this->region.$this->who.$this->endpoint.$username);
        $json = json_decode($url, true);

        foreach ($json['matches'] as $data) {


            if (isset($data)) {

                $user = new User();

                // Pour chaque setter (setUsername(), setRole()...) on appelle la fonction interne setValue() qui
                // prend un certain nombre de paramètres. Cela évite une série de if/else redondants
                // DRY Code : Don't Repeat Your Code
                // Le dernier paramètre permet d'affecter le username, valeur qui n'est de base pas dans la BDD.
                $user = $this->setValue($data, 'username', $username, 'setUsername', $user, $entityManager, false);
                $user = $this->setValue($data, 'gameId', $data['gameId'], 'setGameId', $user, $entityManager);
                $user = $this->setValue($data, 'role', $data['role'], 'setRole', $user, $entityManager);
                $user = $this->setValue($data, 'lane', $data['lane'], 'setLane', $user, $entityManager);


                $entityManager->persist($user);
            } else {
                $err = 'erreur';
                dump($err);
            }
            $entityManager->flush();
        }


    }

    protected function setValue($data, $key, $value, $action, $object, $entityManager, $exists = true)
    {

        if (!$exists || array_key_exists($key, $data)) {
            $object->$action($value);

        } else {
            $object->$action('undefined');

        }

        $entityManager->persist($object);

        return $object;

    }


    /**
     * @Route("/", name="api")
     */
    public
    function index(UserRepository $api): Response
    {
        $users = $api->findAll();

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
            'users' => $users
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_details")
     */
    public function showUserDetails(User $user)
    {


        return $this->render('api/details.html.twig', [
            'user' => $user
        ]);
    }
}
