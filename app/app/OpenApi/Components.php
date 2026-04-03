<?php

namespace App\OpenApi;

use Modules\Order\Enums\OrderStatus;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductResource',
    required: ['id', 'name', 'price', 'category', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Margherita'),
        new OA\Property(property: 'description', type: 'string', example: 'Classic pizza with mozzarella and tomato sauce.', nullable: true),
        new OA\Property(property: 'price', type: 'number', format: 'float', example: 499.0),
        new OA\Property(property: 'weight', type: 'number', format: 'float', example: 450, nullable: true),
        new OA\Property(property: 'category', type: 'string', example: 'pizza'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-04-03 12:00:00', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-04-03 12:00:00', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'PaginationLink',
    properties: [
        new OA\Property(property: 'url', type: 'string', example: 'http://localhost/api/products?page=1', nullable: true),
        new OA\Property(property: 'label', type: 'string', example: '&laquo; Previous'),
        new OA\Property(property: 'active', type: 'boolean', example: false),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'PaginationLinks',
    properties: [
        new OA\Property(property: 'first', type: 'string', example: 'http://localhost/api/products?page=1', nullable: true),
        new OA\Property(property: 'last', type: 'string', example: 'http://localhost/api/products?page=1', nullable: true),
        new OA\Property(property: 'prev', type: 'string', example: null, nullable: true),
        new OA\Property(property: 'next', type: 'string', example: null, nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'PaginationMeta',
    properties: [
        new OA\Property(property: 'current_page', type: 'integer', example: 1),
        new OA\Property(
            property: 'from',
            oneOf: [
                new OA\Schema(type: 'integer', example: 1),
                new OA\Schema(type: 'null'),
            ]
        ),
        new OA\Property(property: 'last_page', type: 'integer', example: 1),
        new OA\Property(property: 'path', type: 'string', example: 'http://localhost/api/products'),
        new OA\Property(property: 'per_page', type: 'integer', example: 15),
        new OA\Property(
            property: 'to',
            oneOf: [
                new OA\Schema(type: 'integer', example: 2),
                new OA\Schema(type: 'null'),
            ]
        ),
        new OA\Property(property: 'total', type: 'integer', example: 2),
        new OA\Property(
            property: 'links',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/PaginationLink')
        ),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ProductCollectionResponse',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/ProductResource')
        ),
        new OA\Property(property: 'links', ref: '#/components/schemas/PaginationLinks'),
        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'CartAddItemRequest',
    required: ['product_id', 'quantity'],
    properties: [
        new OA\Property(property: 'product_id', type: 'integer', example: 1),
        new OA\Property(property: 'quantity', type: 'integer', example: 2, minimum: 1),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'CartItemResponse',
    required: ['id', 'cart_id', 'product_id', 'quantity', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 5),
        new OA\Property(property: 'cart_id', type: 'integer', example: 3),
        new OA\Property(property: 'product_id', type: 'integer', example: 1),
        new OA\Property(property: 'quantity', type: 'integer', example: 2),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-04-03T12:10:00.000000Z', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-04-03T12:10:00.000000Z', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'StoreOrderRequest',
    required: ['region', 'city', 'street', 'house', 'postcode'],
    properties: [
        new OA\Property(property: 'region', type: 'string', example: 'Moscow'),
        new OA\Property(property: 'city', type: 'string', example: 'Moscow'),
        new OA\Property(property: 'street', type: 'string', example: 'Tverskaya'),
        new OA\Property(property: 'house', type: 'string', example: '10'),
        new OA\Property(property: 'entrance', type: 'string', example: '2', nullable: true),
        new OA\Property(property: 'apartment', type: 'string', example: '45', nullable: true),
        new OA\Property(property: 'postcode', type: 'string', example: '125009'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'AddressResponse',
    required: ['id', 'user_id', 'region', 'city', 'street', 'house', 'postcode', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 7),
        new OA\Property(property: 'user_id', type: 'integer', example: 4),
        new OA\Property(property: 'region', type: 'string', example: 'Moscow'),
        new OA\Property(property: 'city', type: 'string', example: 'Moscow'),
        new OA\Property(property: 'street', type: 'string', example: 'Tverskaya'),
        new OA\Property(property: 'house', type: 'string', example: '10'),
        new OA\Property(property: 'entrance', type: 'string', example: '2', nullable: true),
        new OA\Property(property: 'apartment', type: 'string', example: '45', nullable: true),
        new OA\Property(property: 'postcode', type: 'string', example: '125009'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-04-03T12:15:00.000000Z', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-04-03T12:15:00.000000Z', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'OrderItemResponse',
    required: ['id', 'order_id', 'product_id', 'quantity', 'price', 'created_at', 'updated_at', 'product'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 11),
        new OA\Property(property: 'order_id', type: 'integer', example: 9),
        new OA\Property(property: 'product_id', type: 'integer', example: 1),
        new OA\Property(property: 'quantity', type: 'integer', example: 2),
        new OA\Property(property: 'price', type: 'number', format: 'float', example: 499),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-04-03T12:15:00.000000Z', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-04-03T12:15:00.000000Z', nullable: true),
        new OA\Property(property: 'product', ref: '#/components/schemas/ProductResource'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'OrderResponse',
    required: ['id', 'user_id', 'address_id', 'status', 'total_price', 'created_at', 'updated_at', 'items', 'address'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 9),
        new OA\Property(property: 'user_id', type: 'integer', example: 4, nullable: true),
        new OA\Property(property: 'address_id', type: 'integer', example: 7),
        new OA\Property(property: 'status', type: 'string', example: OrderStatus::CREATED->value, enum: [
            OrderStatus::CREATED->value,
            OrderStatus::PAID->value,
            OrderStatus::IN_PROGRESS->value,
            OrderStatus::DELIVERING->value,
            OrderStatus::COMPLETED->value,
            OrderStatus::CANCELLED->value,
        ]),
        new OA\Property(property: 'total_price', type: 'number', format: 'float', example: 998),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-04-03T12:15:00.000000Z', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-04-03T12:15:00.000000Z', nullable: true),
        new OA\Property(
            property: 'items',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/OrderItemResponse')
        ),
        new OA\Property(property: 'address', ref: '#/components/schemas/AddressResponse'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'UnauthorizedResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ForbiddenResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'This action is unauthorized.'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ValidationErrorResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(
            property: 'errors',
            type: 'object',
            example: ['quantity' => ['The quantity field must be at least 1.']],
            additionalProperties: new OA\AdditionalProperties(
                type: 'array',
                items: new OA\Items(type: 'string')
            )
        ),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'BusinessErrorResponse',
    properties: [
        new OA\Property(property: 'error', type: 'string', example: 'В корзине нет товаров'),
    ],
    type: 'object'
)]
class Components {}
