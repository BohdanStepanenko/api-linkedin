<?php
declare(strict_types=1);

namespace App;

use Exception;
use App\Output;
use GuzzleHttp\Client;

class Callback
{
    private const GRANT_TYPE = 'authorization_code';
    
    /**
     * Get LinkedIn API access token
     */
    public static function getCallback(): void 
    {
        try {
            $client = new Client(['base_uri' => BASE_URL]);
            $response = $client->request('POST', '/oauth' . REST_VERSION . 'accessToken', [
                'form_params' => [
                    "grant_type" => self::GRANT_TYPE,
                    "code" => $_GET['code'],
                    "redirect_uri" => REDIRECT_URL,
                    "client_id" => CLIENT_ID,
                    "client_secret" => CLIENT_SECRET,
                ],
            ]);
        
            // Store token
            $data = json_decode($response->getBody()->getContents(), true);         
            session_unset();
            $_SESSION['access_token'] = $data['access_token'];
            Output::writeTokenToFile();
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
