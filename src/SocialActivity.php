<?php
declare(strict_types=1);

namespace App;

use Exception;
use App\Output;
use GuzzleHttp\Client;

class SocialActivity
{
    /**
     * Generate comments data JSON
     */
    public function getCommentsData(): string
    {
        $result = [];
        $post_comments = $this->getPostComments();

        foreach($post_comments as $comments) {
            foreach($comments['elements'] as $comment) {
                $author = [
                    'author' => $this->getCommentAuthorName($comment['actor'])
                ];
                $reply_script = [
                    'reply_script' => $this->replyCommentScript($comment['actor'], $comment['id'], 'Reply #' . uniqid())
                ];
                array_push($comment, $author + $reply_script); 
                array_push($result, $comment);
            }
        }

        Output::writeCommentsDataToFile($result);

        return json_encode($result);
    }

    /**
     * Get posts comments
     */
    public function getPostComments(): array
    {
        $post_comments = [];

        foreach($_SESSION['company_posts'] as $post) {
            try {
                $client = new Client(['base_uri' => API_URL]);
                $response = $client->request('GET', REST_VERSION . SOCIAL_ACTIONS_CALL . $post . PROJECTION_COMMENTS, [
                    'headers' => [
                        "Authorization" => "Bearer " . $_SESSION['access_token'],
                    ],
                ]);
                
                $comment = json_decode($response->getBody()->getContents(), true);
                $link = [
                    'link' => BASE_URL . FEED_UPDATE_ACTION . $post
                ];
                
                $data = array_merge($comment, $link);
                array_push($post_comments, $data);
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        }
        
        return $post_comments;
    }
    
    /**
     * Get comment author name
     */
    public function getCommentAuthorName(string $author_urn): array
    {        
        $author_id = $this->detachAuthorIdFromUrn($author_urn);
        
        if(ctype_digit($author_id)) {
            $url = REST_VERSION . ORGANIZATION_ACTIONS_CALL . $author_id . PROJECTION_AUTHOR_NAME;
        } else {
            $url = REST_VERSION . PEOPLE_ACTIONS_CALL . '(id:' . $author_id . ')' . PROJECTION_AUTHOR_FULLNAME;
        }

        try {
            $client = new Client(['base_uri' => API_URL]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    "Authorization" => "Bearer " . $_SESSION['access_token'],
                    "X-RestLi-Protocol-Version" => "2.0.0",
                ],
            ]);

            $author_data = json_decode($response->getBody()->getContents(), true); 
        } catch(Exception $e) {
            echo $e->getMessage();
        }

        return $author_data;
    }

    /**
     * Generate script for comment reply
     */
    public function replyCommentScript(
        string $author_urn, 
        string $activity, 
        string $message
        ): array {
        $reply = array (
            'actor' => $author_urn,
            'object' => 'urn:li:activity:' . $activity,
            'message' => 
                array (
                    'text' => $message,
                ),
            );

        return $reply;
    }

    /**
     * Detach author ID from URN
     */
    public function detachAuthorIdFromUrn(string $author_urn): string
    {
        $organization = mb_strrpos($author_urn, 'organization');

        if($organization) {
           $id = mb_substr($author_urn, 20);
        }
        else {
            $id = mb_substr($author_urn, 14);
        }

        return $id;
    }
}
