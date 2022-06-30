<?php

define('CLIENT_ID', 'YOUR_CLIENT_ID');
define('CLIENT_SECRET', 'YOUR_CLIENT_SECRET');
define('SCOPES', 'YOUR_PERMISSIONS');
define('REDIRECT_URL', 'YOUR_REDIRECT_URL');
define('BASE_URL', 'https://www.linkedin.com');
define('API_URL', 'https://api.linkedin.com');
define('AUTH_URL', BASE_URL . '/oauth/v2/authorization?response_type=code&client_id=' . CLIENT_ID . '&redirect_uri=' . REDIRECT_URL . '&scope=' . SCOPES);
define('ROOT', dirname(__DIR__));
define('PROJECTION_COMMENTS', '/comments?projection=(elements(*(actor, message, id)))');
define('PROJECTION_AUTHOR_NAME', '?projection=(localizedName)');
define('PROJECTION_AUTHOR_FULLNAME', '?projection=(localizedFirstName, localizedLastName)');
define('REST_VERSION', '/v2/');
define('SOCIAL_ACTIONS_CALL', 'socialActions/');
define('ORGANIZATION_ACTIONS_CALL', 'organizations/');
define('PEOPLE_ACTIONS_CALL', 'people/');
define('FEED_UPDATE_ACTION', '/feed/update/');
