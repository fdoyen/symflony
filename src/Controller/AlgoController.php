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
}
