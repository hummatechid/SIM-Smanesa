<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */

    'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */

    'projects' => [
        'app' => [

            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */

            'credentials' => env('FIREBASE_CREDENTIALS', env('GOOGLE_APPLICATION_CREDENTIALS')),
            // 'credentials' => [
            //     "type" => "service_account",
            //     "project_id" => "smanesa-app",
            //     "private_key_id" => "c1c37d86a05402ceef62aaece9c3121dd8ae3fb8",
            //     "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC6Bw7Nx9n/ArFZ\n4UjZb/CJwOw7Pn3SHDWxsRcBDVUa3wZ1uzHtkRSK+IlIOASOJ/rG4KUpxPO6c59C\nGl06mbOxOYXyfckkFDihKIUHZqaJr+L9orUa9xb8pvsaOB5IaHcmldZbxOaAaS8S\npyXv7XfGOBakJJ+ATHA2Q+wJ+dg+45AV2tPcLXAgMqEK4Yz5DBaw3nLMGjs1z1rr\nfRqAoyZleNREb+zz0HUGWWPjeWcpK9CW5+bwVBe58A83kHQMJBJh1pOWOsvJB5xT\naPOsr8Mp9fPZQYjZ8pZ9vFqYAaiEJE+gmL8hXtgYhuCHyxLk2aKMjAA84KIkNjd/\nCWB8+191AgMBAAECggEAR+r/maWoTRLE7ssOR2Wj2BW6HkNQEEEwrvQYQSNe4gcT\n78MBj+cVSo7AQCfL9wtUw7tIjwfh9BTMHpmjrkqKsjrbYn6nmueoZwGLwGs6eiOB\n/W2AV57sB5wkmqPUbUjZu5SU7SCHvmArvynYnFOxiPPZKPcxpL+oaApFQCwTomfc\nUCADi32Z88EOEeiJsKhYr6r3Kd+GBcIznWLifeVaZEZS1unmZ1jBezO0/CMXHi7S\nywHvyZY8Z8ZNfCA+490FTZLbjUHBR0kxvZj5trT/T5xBO6OAs1RKVIHTgYssTZ69\nZIgrRmJB8XjoNMRlwpEPu/M+vpammShg0/bNcv5RdwKBgQD6azZW0ZMFmu63LZDs\nY8GvlX+FApWwzva9mTPxAwJkQfINRtkKIHiNs2diHU8deBgmt8/f3cbEr4E13Egr\nyY7smHMj/FLzbF01BYz/8YFgcY30uSMrJ2e1BLeV6/HX0QKWQffa4mvcepu3y15L\n6YHs2sbp2G0vlD+3yrmznP58mwKBgQC+LHSWtej0f0idGYigsTzpExQ2I3Tj/bY2\nMa4gpZsjO1wRCQzKnSdtJZjAWOJ2A+PJNz0kRdWslRFhHqQP0WswZmSSRZwj/XDV\n8e5JphEWweNfgOhSQuss4YSF6ZVOR4YRGZUJV5pC6yE74uuL/W5PMbtw9MrvqCg7\nGtXP06XtLwKBgA7OdgkOHTZQodq82/vOLi6WlVKlA3rP0fEF9PdEKUkKSSiVztF1\neAtQ10UK3Jmudyca6TSgZVdN1UL9AltscIH2xaAUFEeGWcB/0oVznyW3sSAOLlEn\n5E7kt4Iq0ELlfdSqk+AiUSRZL4nfgXTVmrQJf4dmH5aXmmjgmLhRCQ8PAoGAIJe9\n45aJtl2aNFKL9/5oUgnTDPRGtExnTiJ1lWLMk904ijLi3lbQywQGifCx30gLYhD1\niaHuXQWvVNXLiGp71G+4DNmhSQnGrOQp0rWDeEMClPui1XZil+6Op+TAkgnwED4y\nLMe2yIFD0N+zPurQXQJI/bL2lNxdCMQwoM3FQkECgYEAp9WMRkTTTvIit1ww51a0\n20kWqmuOaIuKWVk1E7F84ik0UrENpjca5WmklXh2uqAOaPK53WE28ZlAp//JXdYP\nSPRuFXSqJOr5px7x1umNTvoNCvinMuydhwX1jXbBitS6/9qqrQxJ3AV+DGeH8VRX\njN0wiw8D6VSkktjp+RZIm9E=\n-----END PRIVATE KEY-----\n",
            //     "client_email" => "firebase-adminsdk-a5kpn@smanesa-app.iam.gserviceaccount.com",
            //     "client_id" => "115970911275373275025",
            //     "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
            //     "token_uri" => "https://oauth2.googleapis.com/token",
            //     "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
            //     "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-a5kpn%40smanesa-app.iam.gserviceaccount.com",
            //     "universe_domain" => "googleapis.com"
            // ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [

                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */

                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */

                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],

            ],

            'dynamic_links' => [

                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */

                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [

                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),

            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */

            'http_client_options' => [

                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */

                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 *
                 * The default time out can be reviewed at
                 * https://github.com/kreait/firebase-php/blob/6.x/src/Firebase/Http/HttpClientOptions.php
                 */

                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

                'guzzle_middlewares' => [],
            ],
        ],
    ],
];
