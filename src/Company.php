<?php
declare(strict_types=1);

namespace App;

use Exception;
use GuzzleHttp\Client;

class Company
{
    /**
     * Get company ID by name
     */
    public function getCompanyId(string $company_name): int
    {
        try {
            $client = new Client(['base_uri' => 'https://api.linkedin.com']);
            $response = $client->request('GET', '/v2/organizations?q=vanityName&vanityName=' . $company_name, [
                'headers' => [
                    "Authorization" => "Bearer " . $_SESSION['access_token'],
                ],
            ]);

            $company_data = json_decode($response->getBody()->getContents(), true);
            $company_id = $company_data['elements'][0]['id'];
        } catch(Exception $e) {
            echo $e->getMessage();
        }

        return $company_id;
    }

    /**
     * Get company posts
     */
    public function getCompanyPosts(int $company_id): array
    {
        $posts = [];
     
        try {
            $client = new Client(['base_uri' => 'https://api.linkedin.com']);
            $response = $client->request('GET', '/v2/posts?author=urn%3Ali%3Aorganization%3A' . $company_id . '&isDsc=false&q=author', [
                'headers' => [
                    "Authorization" => "Bearer " . $_SESSION['access_token'],
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            foreach($data['elements'] as $element) {
                array_push($posts, $element['id']);
            }            

            $_SESSION['company_posts'] = $posts;
        } catch(Exception $e) {
            echo $e->getMessage();
        }

        return $posts;
    }
}
