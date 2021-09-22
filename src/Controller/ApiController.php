<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{
	protected $base_url = "http://51.255.160.47:8181/";
	protected $region = "euw1/";
	protected $who = "passerelle/";
	protected $endpoint = "getHistoryMatchList/";
    protected $errors = [
        404 => "Not found in DB",
        403 => "Forbidden",
    ];

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

    /**
     * @Route("/getuser/{id}", name="get_user")
     */
    public function getUser($id, UserRepository $api){
        $message = null;
        $user = $api->findOneBy(['id' => $id]);
        try{
            if(!$user){
                throw new \Exception('The match with id '.$id.' does not exists in database');
            }else{
            }

            $message = "Match found !";
            $code = "success";
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = "danger";
        }

        // Exemple avec division par zéro d'un nombre
        // $x = 0;
        // $message = null;
        // $result = 10;
        // try {
        //     if (!$x) {
        //         throw new \Exception('Division par zéro.');
        //     }elseif($x > 9){
        //         throw new \Exception('Nombre > 9...');
        //     }
        //     $result = 10 / $x;
        // } catch (\Exception $e) {
        //     // Créer votre objet à insérer en BDD...
        //     //$object->message = $e->getMessage();
        //     //$object->persist();
        //     $message = $e->getMessage();
        // }

        return $this->render('api/error.html.twig', [
            'controller_name' => 'ApiController',
            'message' => $message,
            'alert' => $code,
            'user' => $user
        ]);
    }

    /**
     * @Route("/api/match/{id}", name="api_get_match")
     */
    public function getMatch($id = 0, UserRepository $api){
        $result = file_get_contents('http://ddragon.leagueoflegends.com/cdn/9.3.1/data/en_US/champion.json');
        dd(json_decode($result));
        // $result = $api->findOneBy(['id' => $id]);
        // if(!$result){
        //     $code = 403;
        //     $result = $this->errors[$code];
        // }else{
        //     $code = 200;
        // }
        // $response = array('code' => $code, 'result' => $result);
        // return new JsonResponse($response, $code);
    }

    /**
     * @Route("/dragon/item/all", name="data_item_all")
     */
    public function dataItemAll($id = 0, UserRepository $api){
        $result = file_get_contents('http://ddragon.leagueoflegends.com/cdn/9.3.1/data/en_US/item.json');
        $json = json_decode($result, true);
        $items = [];
        foreach($json['data'] as $item){
            $name = $item['name'];
            $description = $item['plaintext'];
            $texts = [
                "name" => $name,
                "description" => $description
            ];
            array_push($items,$texts);
        }
        return $this->render('dragon/allitems.html.twig', [
            'items' => $items
        ]);
    }
}

// 22/09
// 1°) Parcourir le fichier https://ddragon.leagueoflegends.com/cdn/9.3.1/data/en_US/item.json
// --- file_get_contents puis un json_decode et vous avez un tableau PHP dans lequel vous pouvez boucler !
// 2°) Récupérer pour CHAQUE item, le "name" et le "plaintext", et affichez ça dans un front twig, un item par ligne.

// 3°) - challengeant
// Dans le fichier d'items du 1°), récupérer les 3 items les plus cher du jeu (clé "total")
// 4°) - challengeant
// Trouver la liste des items qui nécessitent la "long sword" (1036), la clé c'est "into", et les champs a afficher pour chaque item sont : le "name" et le "plaintext"

// 21/09
// part 1
// 1° Get champion ID from historyMatchList/azerty
// 2° Get detail match by ID from historyMatch/{ID DU MATCH}
// 3° Loop into "participants" from match details to find the participant with champion ID (1°)
// --- for each item into the loop, stock the current participant object into variable
// --- when championId match the key championId into the current participant object, break the loop
// 4° show dates, victory or not, gameMode & champion name + image

// part 2
// 5° find the champion into the data dragon champion JSON file by championID
// --- go to the participantIdentities key from historyMatch API URL to get the participant full identity
// 6° show image + summonerName of all 10 participants