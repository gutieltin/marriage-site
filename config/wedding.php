<?php

return [
    // Secret used by the admin endpoints (sent in the X-Admin-Token header).
    'admin_token' => env('WEDDING_ADMIN_TOKEN'),

    // Public URL of the invitation site, used when generating guest links.
    'frontend_url' => env('WEDDING_FRONTEND_URL', 'http://localhost:3000'),
];
