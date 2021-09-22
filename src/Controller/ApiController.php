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

    // /**
    //  * @Route("/fetchapi", name="fetchapi")
    //  */
    // public function fetchApi(EntityManagerInterface $entityManager)
    // {
    // 	// Appeler cette route une seule fois sinon multiple doublons en BDD
    //     $username = 'azerty';

    //     $url = file_get_contents($this->base_url.$this->region.$this->who.$this->endpoint.$username);
    //     $json = json_decode($url, true);

    //     foreach ($json['matches'] as $data) {


    //         if (isset($data)) {

    //             $user = new User();

    //             // Pour chaque setter (setUsername(), setRole()...) on appelle la fonction interne setValue() qui
    //             // prend un certain nombre de paramètres. Cela évite une série de if/else redondants
    //             // DRY Code : Don't Repeat Your Code
    //             // Le dernier paramètre permet d'affecter le username, valeur qui n'est de base pas dans la BDD.
    //             $user = $this->setValue($data, 'username', $username, 'setUsername', $user, $entityManager, false);
    //             $user = $this->setValue($data, 'gameId', $data['gameId'], 'setGameId', $user, $entityManager);
    //             $user = $this->setValue($data, 'role', $data['role'], 'setRole', $user, $entityManager);
    //             $user = $this->setValue($data, 'lane', $data['lane'], 'setLane', $user, $entityManager);


    //             $entityManager->persist($user);
    //         } else {
    //             $err = 'erreur';
    //             dump($err);
    //         }
    //         $entityManager->flush();
    //     }


    // }

    // protected function setValue($data, $key, $value, $action, $object, $entityManager, $exists = true)
    // {

    //     if (!$exists || array_key_exists($key, $data)) {
    //         $object->$action($value);

    //     } else {
    //         $object->$action('undefined');

    //     }

    //     $entityManager->persist($object);

    //     return $object;

    // }


    // /**
    //  * @Route("/", name="api")
    //  */
    // public
    // function index(UserRepository $api): Response
    // {
    //     $users = $api->findAll();

    //     return $this->render('api/index.html.twig', [
    //         'controller_name' => 'ApiController',
    //         'users' => $users
    //     ]);
    // }

    // /**
    //  * @Route("/user/{id}", name="user_details")
    //  */
    // public function showUserDetails(User $user)
    // {


    //     return $this->render('api/details.html.twig', [
    //         'user' => $user
    //     ]);
    // }

    // /**
    //  * @Route("/getuser/{id}", name="get_user")
    //  */
    // public function getUser($id, UserRepository $api){
    //     $message = null;
    //     $user = $api->findOneBy(['id' => $id]);
    //     try{
    //         if(!$user){
    //             throw new \Exception('The match with id '.$id.' does not exists in database');
    //         }else{
    //         }

    //         $message = "Match found !";
    //         $code = "success";
    //     }catch(\Exception $e){
    //         $message = $e->getMessage();
    //         $code = "danger";
    //     }

    //     // Exemple avec division par zéro d'un nombre
    //     // $x = 0;
    //     // $message = null;
    //     // $result = 10;
    //     // try {
    //     //     if (!$x) {
    //     //         throw new \Exception('Division par zéro.');
    //     //     }elseif($x > 9){
    //     //         throw new \Exception('Nombre > 9...');
    //     //     }
    //     //     $result = 10 / $x;
    //     // } catch (\Exception $e) {
    //     //     // Créer votre objet à insérer en BDD...
    //     //     //$object->message = $e->getMessage();
    //     //     //$object->persist();
    //     //     $message = $e->getMessage();
    //     // }

    //     return $this->render('api/error.html.twig', [
    //         'controller_name' => 'ApiController',
    //         'message' => $message,
    //         'alert' => $code,
    //         'user' => $user
    //     ]);
    // }


    // // 21/09
    // // part 1
    // // 1° Get champion ID from historyMatchList/azerty
    // // 2° Get detail match by ID from historyMatch/{ID DU MATCH}
    // // 3° Loop into "participants" from match details to find the participant with champion ID (1°)
    // // --- for each item into the loop, stock the current participant object into variable
    // // --- when championId match the key championId into the current participant object, break the loop
    // // 4° show dates, victory or not, gameMode & champion name + image

    // // part 2
    // // 5° find the champion into the data dragon champion JSON file by championID
    // // --- go to the participantIdentities key from historyMatch API URL to get the participant full identity
    // // 6° show image + summonerName of all 10 participants

    // /**
    //  * @Route("/api/show/matches", name="api_show_matches")
    //  */
    // public function getMatch($id = 0, UserRepository $api){
    //     // Version sale / pas optimisée
    //     $username = 'azerty';
    //     $currentChampion = null;
    //     $currentParticipant = null;
    //     $champions = file_get_contents('http://ddragon.leagueoflegends.com/cdn/9.3.1/data/en_US/champion.json');
    //     $championsArray = json_decode($champions, true);
    //     $url = file_get_contents($this->base_url.$this->region.$this->who.$this->endpoint.$username);
    //     $json = json_decode($url, true);
    //     $arrayMatches = [];
    //     foreach($json['matches'] as $key => $match){
    //         $championId = $match['champion'];
    //         $url = file_get_contents($this->base_url.$this->region.$this->who."getHistoryMatch/".$match['gameId']);
    //         $detailMatch = json_decode($url, true);
    //         foreach($detailMatch['participants'] as $key => $participant){
    //             if($participant['championId'] === $championId){
    //                 $currentParticipant = $participant;
    //                 break;
    //             }
    //         }
    //         foreach($championsArray['data'] as $key => $champion){
    //             if($championId === (int) $champion['key']){
    //                 $currentChampion = $champion;
    //                 break;
    //             }
    //         }
    //         array_push($arrayMatches, [
    //                 'gameMode' => $detailMatch['gameMode'],
    //                 'win' => $currentParticipant['stats']['win'],
    //                 'gameCreation' => $detailMatch['gameCreation'],
    //                 'gameDuration' => $detailMatch['gameDuration'],
    //                 'championName' => $currentChampion['name'],
    //                 'championImg' => "https://opgg-static.akamaized.net/images/lol/champion/".$champion['image']['full']
    //             ]
    //         );
    //     }
    //     return $this->render('api/historyMatches.html.twig', [
    //         'arrayMatches' => $arrayMatches
    //     ]);
    // }

    // /**
    //  * @Route("/dragon/item/all", name="data_item_all")
    //  */
    // public function dataItemAll($id = 0, UserRepository $api){
    //     $result = file_get_contents('http://ddragon.leagueoflegends.com/cdn/9.3.1/data/en_US/item.json');
    //     $json = json_decode($result, true);
    //     $items = [];
    //     foreach($json['data'] as $item){
    //         $name = $item['name'];
    //         $description = $item['plaintext'];
    //         $texts = [
    //             "name" => $name,
    //             "description" => $description
    //         ];
    //         array_push($items,$texts);
    //     }
    //     asort($items);
    //     dd($items);
    //     return $this->render('dragon/allitems.html.twig', [
    //         'items' => $items
    //     ]);
    // }

    // /**
    //  * @Route("/dragon/item/expensives", name="data_item_expensives")
    //  */
    // public function dataItemExpensives($id = 0, UserRepository $api){
    //     $result = file_get_contents('http://ddragon.leagueoflegends.com/cdn/9.3.1/data/en_US/item.json');
    //     $json = json_decode($result, true);
    //     $tempitems = [];
    //     foreach($json['data'] as $item){
    //         $cheapiest = 0;
    //         $firstrow = true;
    //         $new = null;
    //         //$json['data']['clé']['gold']['total']
    //         if(count($tempitems) >= 3){ // Si notre tableau a au moins 3 items on peut commencer à comparer lequel des 3 est le moins cher et donc à remplacer par le prochain item bouclé
    //             foreach($tempitems as $key => $tempitem){ // Pour chacun des 3 items...
    //                 if($firstrow){
    //                     // Bouléen pour vérifier si on est au premier item comparé (cheapiest = 0)
    //                     $firstrow = false; // On est plus au premier donc on met false
    //                     $cheapiest = $tempitem['gold']['total']; // On remplace 0 par le prix de l'item
    //                     $cheapiestKey = $key; // On stock la clé de l'item
    //                 }
    //                 $newPrice = $tempitem['gold']['total'];
    //                 // Pour plus de lisibilité on stock dans $newPrice
    //                 if($cheapiest > $newPrice){ // On compare si $newPrice est moins cher que $cheapiest
    //                     $cheapiest = $newPrice; // On swap...
    //                     $cheapiestKey = $key;
    //                 }
    //                 if($tempitem['gold']['total'] < $item['gold']['total']){
    //                     $new = $item; // Futur item plus cher
    //                 }
    //                 //dump($cheapiest);
    //                 //dump($expensive);
    //             }
    //             unset($tempitems[$cheapiestKey]); // On dégage le cheapiest
    //             if($new !== null){ // Si l'objet courant est moins cher que les 3 actuels du tableau, on ignore
    //                 array_push($tempitems, $new); // On ajoute le nouveau (pour rester à 3 items) 
    //             }
    //         }else{
    //             array_push($tempitems,$item); // On push les 3 premiers items
    //         }
    //     }
    //     // we can't sort by most valuable, because we have only objects
    //     dd($tempitems);
    //     return $this->render('dragon/allitems.html.twig', [
    //         'items' => $items
    //     ]);
    // }

    // /**
    //  * @Route("/dragon/item/build", name="data_item_build")
    //  */
    // public function dataItemBuild($id = 0, UserRepository $api){
    //     $result = file_get_contents('http://ddragon.leagueoflegends.com/cdn/9.3.1/data/en_US/item.json');
    //     $staticUrl = "https://opgg-static.akamaized.net/images/lol/item/";
    //     $json = json_decode($result, true);
    //     $id = 4403;
    //     $itemUsed = $json['data'][$id]; // Stat-Stick of Stoicism
    //     $finalItem = [
    //             "name" => $itemUsed['name'],
    //             "description" => $itemUsed['plaintext'],
    //             "price" => $itemUsed['gold']['total'],
    //             "image" => $staticUrl.$id.".png"
    //         ];
    //     $items = [];
    //     foreach($itemUsed['from'] as $key => $itemId){ // 'into' => est utilisé pour... 'from' => est construit à partir...
    //         array_push($items, [
    //             "name" => $json['data'][$itemId]['name'],
    //             "description" => $json['data'][$itemId]['plaintext'],
    //             "price" => $json['data'][$itemId]['gold']['total'],
    //             "image" => $staticUrl.$itemId.".png"
    //         ]);
    //     }
    //     return $this->render('dragon/build.html.twig', [
    //         'items' => $items,
    //         'finalItem' => $finalItem
    //     ]);
    // }
}

// get gold from the item
// Check if array already have 3 items into the temporary array
// if >= 3
// Check gold and pop the cheapiest one
// then
// push it into the temporary array

/*gold_item6 = 550
check si 550 est supérieur à l un des trois golds déjà dans le tableau
SI OUI, on place item4 au bon endroit
SINON on ignore
[550, 500, 700]
arsort 700 550 500
*/

// 22/09
// 1°) Parcourir le fichier https://ddragon.leagueoflegends.com/cdn/9.3.1/data/en_US/item.json
// --- file_get_contents puis un json_decode et vous avez un tableau PHP dans lequel vous pouvez boucler !
// 2°) Récupérer pour CHAQUE item, le "name" et le "plaintext", et affichez ça dans un front twig, un item par ligne.

// 3°) - challengeant
// Dans le fichier d'items du 1°), récupérer les 3 items les plus cher du jeu (clé "total")
// 4°) - challengeant
// Trouver la liste des items qui nécessitent la "long sword" (1036), la clé c'est "into", et les champs a afficher pour chaque item sont : le "name" et le "plaintext"
