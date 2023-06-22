<?php
namespace Src\Interfaces;

interface HttpResponseConstantsInterface {
    CONST HTTP_200 = 'HTTP/1.1 200 OK';
    CONST HTTP_201 = 'HTTP/1.1 201 Created';
    CONST HTTP_401 = 'HTTP/1.1 401 Unauthorized';
    CONST HTTP_404 = 'HTTP/1.1 404 Not Found';
    CONST HTTP_422 = 'HTTP/1.1 422 Unprocessable Entity';
    CONST HTTP_500 = 'HTTP/1.1 500 Server Error';
}