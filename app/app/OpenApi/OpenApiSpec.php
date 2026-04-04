<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'API documentation for products, cart and orders endpoints.',
    title: 'E-Commerce API'
)]
#[OA\Server(
    url: '/',
    description: 'Current application server'
)]
#[OA\Tag(
    name: 'Products',
    description: 'Product catalog endpoints'
)]
#[OA\Tag(
    name: 'Cart',
    description: 'Cart management endpoints'
)]
#[OA\Tag(
    name: 'Orders',
    description: 'Order management endpoints'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    description: 'JWT token passed in the Authorization header as Bearer {token}',
    bearerFormat: 'JWT',
    scheme: 'bearer'
)]
class OpenApiSpec {}
