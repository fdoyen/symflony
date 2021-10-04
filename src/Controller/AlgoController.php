<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlgoController extends AbstractController
{

    /**
     * @Route("/merge_sort", name="merge_sort")
     */
    public function merge_sort(){
        $array = [3,4,6,2,5,1,8,7];
        $array = $this->fusion($array);

            // echo "<pre>";
            // print_r($right);
            // echo "</pre>";
       return $this->render('algo/plusoumoins.html.twig', [
        'controller_name' => 'AlgoController'
]);
    }

    protected function fusion($tab){ // [6, 2]
        $left = array_slice($tab, 0, floor(count($tab) / 2)); // [6]
        $right = array_slice($tab, floor(count($tab) / 2)); // [2]
        if(count($tab) > 2){
            $this->fusion($left);
            $this->fusion($right);
        }else{
            if($left[0] > $right[0]){
                $tab = array_merge($right, $left);
            }
            return $tab;
        }
    }

    /** 
     * @Route("/algo_plusoumoins", name="algo_plusoumoins")
     */
    public function algo_plusoumoins(): Response
    {
        $msg = "";
        if(isset($_GET['valeur'])){
            $to_find = (int)$_GET['valeur'];
            $min = 0;
            $max = 10000;
            $median = 0;
            $count = 0;
            $countfor = 0;

            $time_start = microtime(true);
            for($i = $min; $i <= $max; $i++){
                $countfor++;
                if($i === $to_find){
                    break;
                }
            }
            echo "Linear search : Trouvé en ".$countfor." tour(s) !<br><br>";
            
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start)/60;
            echo '<b>Linear search : Total Execution Time:</b> '.number_format($execution_time, 10).' Mins';

            $time_start = microtime(true);
            while($to_find !== $median){ // tant que le nb à trouver est différent de médian...
                $count++;
                // Calcul du médian a chaque tour
                $median = (int) floor(($min + $max) / 2);
                if($to_find === $median){
                    // trouvé
                    $msg = "Binary search : Trouvé en ".$count." tour(s) !";
                }elseif($to_find < $median){
                    // C'est moins
                    $msg = "C'est moins !";
                    $max = $median;
                }elseif($to_find > $median){
                    // C'est plus
                    $msg = "C'est plus !";
                    $min = $median;
                }else{
                    // NOTHING
                }
            }
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start)/60;
            echo '<br><br><b>Binary search : Total Execution Time:</b> '.number_format($execution_time, 10).' Mins';


            

        }

       return $this->render('algo/plusoumoins.html.twig', [
                 'controller_name' => 'AlgoController',
                 'msg' => $msg
       ]);
    }

    // /**
    //  * @Route("/algo", name="algo")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('algo/index.html.twig', [
    //     	'result' => $this->dimensionalSearchRecursive(),
    //         'controller_name' => 'AlgoController',
    //     ]);
    // }


    // //[4, 6, 3, 10, 16, 4, 2, 45, 4, 4, 4, 4, 4, etc...]
    // protected function dimensionalSearchRecursive($array = null){
    //     if($array === null){
    //         $array = [ [ 4, 6, 3 ], [ 10, 16, 4 ], [ 2, [45, [4,4,4,4,4]]], [ 25, 10, 5 ], [ 12 ], [ 12, 14 ] ];
    //     }
    //     $newArray = [];
    //     for($i = 0; $i < count($array); $i++){
    //         if(is_array($array[$i])){
    //             $returnedArray = $this->dimensionalSearchRecursive($array[$i]);
    //             for($j = 0; $j < count($returnedArray); $j++){
    //                 array_push($newArray, $returnedArray[$j]);
    //             }
    //         }else{
    //             array_push($newArray, $array[$i]);
    //         }
    //     }
    //     return $newArray;
    // }

    // protected function dimensionalSearch(){
    // 	$array = [ [ 4, 6, 3 ], [ 10, 16, 4 ], [ 2, [45, 56]], [ 25, 10, 5 ], [ 12 ], [ 12, 14 ] ];
    // 	$newArray = [];
    // 	for($i = 0; $i < count($array); $i++){
	// 		if(is_array($array[$i])){
	// 			for($j = 0; $j < count($array[$i]); $j++){
	// 				array_push($newArray, $array[$i][$j]);
	// 			}
	// 		}else{
	// 			array_push($newArray, $array[$i]);
	// 		}
	// 	}
    //     dd($newArray);
	// 	return $newArray;
    // }

    // protected function fact($nb){
    // 	if($nb !== 0){
    // 		return $nb * $this->fact($nb - 1);
    // 	}else{
    // 		return 1;
    // 	}
    // }

    // /**
    //  * @Route("/algo/binarysearch", name="algo_binarysearch")
    //  */
    // public function algoBinarySearch(){
    //     $username = 'azerty';
    //     $championToFind = "Katarina";
    //     $champions = file_get_contents('http://ddragon.leagueoflegends.com/cdn/9.3.1/data/en_US/champion.json');
    //     $championsArray = json_decode($champions, true);
    //     $counter = 0;
    //     $orderedChampions = [];
    //     foreach($championsArray['data'] as $key => $champion){
    //         array_push($orderedChampions, $champion);
    //     }
    //     $this->binary($orderedChampions, $counter, $championToFind);
        
    // }

    // private function binary($orderedChampions, $counter, $championToFind){
    //     $counter++;
    //     echo $counter."<br />";
    //     dump($orderedChampions);
    //     $arrayLength = count($orderedChampions);
    //     $middle = (int)floor($arrayLength / 2);
    //     if(strcmp($championToFind, $orderedChampions[$middle]['name']) === 0){
    //         dd($orderedChampions[$middle]);
    //     }elseif(strcmp($championToFind, $orderedChampions[$middle]['name']) < 0){
    //         // first half
    //         $sliced = array_slice($orderedChampions, 0, $middle);
    //         $this->binary($sliced, $counter, $championToFind);
    //     }elseif(strcmp($championToFind, $orderedChampions[$middle]['name']) > 0){
    //         // last half
    //         $sliced = array_slice($orderedChampions, $middle, $arrayLength / 2);
    //         $this->binary($sliced, $counter, $championToFind);
    //     }
    //     return $counter;
    // }
}


// Get the middle
// check if the ID to find is on the first half, or the last half