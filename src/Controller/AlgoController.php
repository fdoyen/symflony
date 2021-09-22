<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlgoController extends AbstractController
{
    /**
     * @Route("/algo", name="algo")
     */
    public function index(): Response
    {
        return $this->render('algo/index.html.twig', [
        	'result' => $this->dimensionalSearchRecursive(),
            'controller_name' => 'AlgoController',
        ]);
    }


    //[4, 6, 3, 10, 16, 4, 2, 45, 4, 4, 4, 4, 4, etc...]
    protected function dimensionalSearchRecursive($array = null){
        if($array === null){
            $array = [ [ 4, 6, 3 ], [ 10, 16, 4 ], [ 2, [45, [4,4,4,4,4]]], [ 25, 10, 5 ], [ 12 ], [ 12, 14 ] ];
        }
        $newArray = [];
        for($i = 0; $i < count($array); $i++){
            if(is_array($array[$i])){
                $returnedArray = $this->dimensionalSearchRecursive($array[$i]);
                for($j = 0; $j < count($returnedArray); $j++){
                    array_push($newArray, $returnedArray[$j]);
                }
            }else{
                array_push($newArray, $array[$i]);
            }
        }
        return $newArray;
    }

    protected function dimensionalSearch(){
    	$array = [ [ 4, 6, 3 ], [ 10, 16, 4 ], [ 2, [45, 56]], [ 25, 10, 5 ], [ 12 ], [ 12, 14 ] ];
    	$newArray = [];
    	for($i = 0; $i < count($array); $i++){
			if(is_array($array[$i])){
				for($j = 0; $j < count($array[$i]); $j++){
					array_push($newArray, $array[$i][$j]);
				}
			}else{
				array_push($newArray, $array[$i]);
			}
		}
        dd($newArray);
		return $newArray;
    }

    protected function fact($nb){
    	if($nb !== 0){
    		return $nb * $this->fact($nb - 1);
    	}else{
    		return 1;
    	}
    }

    /**
     * @Route("/algo/binarysearch", name="algo_binarysearch")
     */
    public function algoBinarySearch(){
        $username = 'azerty';
        $championToFind = "Katarina";
        $champions = file_get_contents('http://ddragon.leagueoflegends.com/cdn/9.3.1/data/en_US/champion.json');
        $championsArray = json_decode($champions, true);
        $counter = 0;
        $orderedChampions = [];
        foreach($championsArray['data'] as $key => $champion){
            array_push($orderedChampions, $champion);
        }
        $this->binary($orderedChampions, $counter, $championToFind);
        
    }

    private function binary($orderedChampions, $counter, $championToFind){
        $counter++;
        echo $counter."<br />";
        dump($orderedChampions);
        $arrayLength = count($orderedChampions);
        $middle = (int)floor($arrayLength / 2);
        if(strcmp($championToFind, $orderedChampions[$middle]['name']) === 0){
            dd($orderedChampions[$middle]);
        }elseif(strcmp($championToFind, $orderedChampions[$middle]['name']) < 0){
            // first half
            $sliced = array_slice($orderedChampions, 0, $middle);
            $this->binary($sliced, $counter, $championToFind);
        }elseif(strcmp($championToFind, $orderedChampions[$middle]['name']) > 0){
            // last half
            $sliced = array_slice($orderedChampions, $middle, $arrayLength / 2);
            $this->binary($sliced, $counter, $championToFind);
        }
        return $counter;
    }
}


// Get the middle
// check if the ID to find is on the first half, or the last half