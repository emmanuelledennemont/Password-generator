<?php

namespace App\Service; 

class PasswordGenerator 
{
    public function generate(int $length, bool $uppercase= false, bool $digits= false , bool $specialCharacters = false): string
    {
        $lowercaseLettersAlphabet = range('a', 'z');
        $uppercaseAlphabet = range('A', 'Z');
        $digitsAlphabet = range(0, 9);
        $specialCharactersAlphabet = [  
        ...range('!', '/'),
        ...range(':', '@'),
        ...range('[', '`'),
        ...range ('{', '~'),
        ];

        $finalAlphabet =  [$lowercaseLettersAlphabet];

        $password = [$this->pickRandomItemAlphabet($lowercaseLettersAlphabet)];

        // if ($uppercase){
        //     $finalAlphabet = array_merge($finalAlphabet, $uppercaseAlphabet);
        //     $password[] = $this->pickRandomItemAlphabet($uppercaseAlphabet);
        // }
      
        // if ($digits) {
        //     $finalAlphabet = array_merge($finalAlphabet, $digitsAlphabet);
        //     $password[] = $this->pickRandomItemAlphabet($digitsAlphabet);
        // }

        // if ($specialCharacters) {
        //     $finalAlphabet = array_merge($finalAlphabet, $specialCharactersAlphabet);
        //     $password[] = $this->pickRandomItemAlphabet($specialCharactersAlphabet);
        // }

        $contraintsMapping = [
            [$uppercase, $uppercaseAlphabet],
            [$digits, $digitsAlphabet], 
            [$specialCharacters, $specialCharactersAlphabet] 
        ];

        foreach ($contraintsMapping as [$constraintEnabled, $constraintAlphabet]) {
            if ($constraintEnabled) {
                $finalAlphabet[] = $constraintAlphabet;

                $password[] = $this->pickRandomItemAlphabet($constraintAlphabet);
            }
        }

       $finalAlphabet = array_merge(...$finalAlphabet);

       $numberOfCharactersRemaining = $length - count($password);

        for ($i = 0; $i < $numberOfCharactersRemaining; $i++) { 
           $password[] = $this->pickRandomItemAlphabet($finalAlphabet);
       }
       
       $password = $this->secureShuffle($password);

       return implode('', $password);;
    }

    private function pickRandomItemAlphabet(array $alphabet) : string 
    {
            return $alphabet [random_int(0, count($alphabet) -1)];
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