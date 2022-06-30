<?php
declare(strict_types=1);

namespace App;

class Output
{
    /**
     * Store token to txt file
     */
    public static function writeTokenToFile(): void
    {
        $file = ROOT . '/output/token.txt';
        file_put_contents($file, $_SESSION['access_token']);
    }

    /**
     * Store comments data to txt file in JSON
     */
    public static function writeCommentsDataToFile(array $data): void
    {
        $file = ROOT . '/output/data.yaml';
        file_put_contents($file, json_encode($data));
    }
}
