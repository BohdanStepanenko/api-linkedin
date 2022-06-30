<?php
declare(strict_types=1);

session_start();

use App\Callback;

require_once('../config/config.php');
require_once('../bootstrap.php');

$company = new App\Company();
$social_activity = new App\SocialActivity();
$_route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$request = match($_route) {
    '' => require ROOT . '/views/login.php',
    'callback' => [
        Callback::getCallback(),
        header("Location: http://api-linkedin/company"),
    ],
    'company' => [
        $company_id = $company->getCompanyId('ys-api'),
        $company->getCompanyPosts($company_id),
        header("Location: http://api-linkedin/comments"),
    ],
    'comments' => [
        print_r($social_activity->getCommentsData()),
    ],
    default => http_response_code(404),
};
