<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_pages')]
    public function index(): Response
    {
        return $this->render('pages/index.html.twig');
    }

    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request): Response
    {
        $length = $request->query->getInt(('length'));
        $uppercase = $request->query->getBoolean('uppercase');
        $digits = $request->query->getBoolean('digits');
        $specialCharacters = $request->query->getBoolean('special_characters');

        $lowercaseLettersSet = range('a', 'z');
        $uppercaseSet = range('A', 'Z');
        $digitsSet = range(0, 9);
        $specialCharactersSet = ['!', '"', '#', '$', '%', '&', "'", '(', ')', '*', '+', ',', '-', '.', '/', ':', ';', '<', '=', '>', '?', '@', '[', '\\', ']', '^', '_', '`', '{', '|', '}', '~'];

        $characters =  $lowercaseLettersSet;

        $password='';

        $password .= $lowercaseLettersSet[random_int(0, count($lowercaseLettersSet)-1)];

        if ($uppercase){
            $characters = array_merge($characters, $uppercaseSet);
            $password .= $uppercaseSet[random_int(0, count($uppercaseSet)-1)];
        }
      
        if ($digits) {
            $characters = array_merge($characters, $digitsSet);
            $password .= $digitsSet[random_int(0, count($digitsSet)-1)];
        }

        if ($specialCharacters) {
            $characters = array_merge($characters, $specialCharactersSet);
            $password .= $specialCharactersSet[random_int(0, count($specialCharactersSet)-1)];
        }

       $numberOfCharactersRemaining = $length - mb_strlen($password);

        for ($i=0; $i < $numberOfCharactersRemaining; $i++) { 
           $password .= $characters [random_int(0, count($characters) -1)];
       }
 
       $password = str_split($password);
       
       $password = $this->secureShuffle($password);

       $password = implode('', $password);

        return $this->render('pages/password.html.twig',  compact('password'));
    }

    private function secureShuffle(array $arr): array
    {
        //Source: https://github.com/lamansky/secure-shuffle/blob/master/src/functions.php

            $length = count($arr);
            for ($i = $length - 1; $i > 0; $i--) {
                $j = random_int(0, $i);
                $temp = $arr[$i];
                $arr[$i] = $arr[$j];
                $arr[$j] = $temp;
            }
            return $arr;
    }
}
