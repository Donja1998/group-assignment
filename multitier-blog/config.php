<?php

// Run `node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"`
// in terminal to generate secret
define('APPLICATION_NAME', 'multitier-blog');
define('JWT_SECRET', 'your_jwt_secret_here');


// Set database connection info here
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_DATABASE', 'multitier_blog');
