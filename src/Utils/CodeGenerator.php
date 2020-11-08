<?php declare(strict_types=1);


namespace App\Utils;


class CodeGenerator
{
    private $alphabet;

    private $alphabetLength;

    public function __construct(string $alphabet = '')
    {
        if ($alphabet === '') {
            $this->setAlphabet(
                implode(range('A', 'Z'))
                . implode(range(1, 9))
            );
        } else {
            $this->setAlphabet($alphabet);
        }
    }

    public function setAlphabet(string $alphabet)
    {
        $this->alphabet = $alphabet;
        $this->alphabetLength = strlen($alphabet);
    }

    public function generate(int $length)
    {
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $randomKey = random_int(0, $this->alphabetLength);
            $code .= $this->alphabet[$randomKey];
        }

        return $code;
    }
}