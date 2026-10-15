
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ONE ICTMS API Documentation</title>

    <link
        rel="stylesheet"
        href="https://unpkg.com/swagger-ui-dist@5.17.14/swagger-ui.css"
    >

    <style>
        body {
            margin: 0;
            background: #fafafa;
        }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>

    <noscript>
        Enable JavaScript to view the interactive documentation.
        <a href="{{ route('swagger.spec') }}">
            Download the OpenAPI specification
        </a>.
    </noscript>

    <script src="https://unpkg.com/swagger-ui-dist@5.17.14/swagger-ui-bundle.js"></script>

    <script>
        window.onload = function () {
            window.ui = SwaggerUIBundle({
                url: {{ Illuminate\Support\Js::from(route('swagger.spec')) }},

                dom_id: '#swagger-ui',

                deepLinking: true,

                presets: [
                    SwaggerUIBundle.presets.apis
                ],

                layout: 'BaseLayout',

                validatorUrl: null,

                /*
                |--------------------------------------------------------------------------
                | JWT HttpOnly Cookie + Laravel CSRF
                |--------------------------------------------------------------------------
                */

                requestInterceptor: function (request) {

                    // Allow the browser to send session and JWT cookies.
                    request.credentials = 'include';

                    // Read Laravel's XSRF-TOKEN cookie.
                    // This is not the HttpOnly JWT cookie.
                    const xsrfCookie = document.cookie
                        .split('; ')
                        .find(cookie =>
                            cookie.startsWith('XSRF-TOKEN=')
                        );

                    if (xsrfCookie) {
                        const token = decodeURIComponent(
                            xsrfCookie.substring(
                                'XSRF-TOKEN='.length
                            )
                        );

                        request.headers = request.headers || {};

                        request.headers['X-XSRF-TOKEN'] = token;
                    }

                    request.headers = request.headers || {};

                    request.headers['Accept'] = 'application/json';

                    return request;
                }
            });
        };
    </script>
</body>
</html>
