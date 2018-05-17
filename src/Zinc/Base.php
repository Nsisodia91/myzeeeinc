<?php

namespace Zinc;

/**
 * Base class to define all variables that must be unique within whole class
 */
class Base
{

    /**
     * Defined API methods that need to be called while accessing different methods
     * @var array
     */
    protected static $api_methods = [
        'create_order'   => 'orders', //create an order request
        'retrieve_order' => 'orders/<request_id>', //create an order request
        'abort_order'    => 'orders/<request_id>/abort', //create an order request
    ];

    /**
     * Defined API HTTP methods that need to be called while accessing different methods
     * @var array
     */
    protected static $api_endpoints = [
        'POST' => [
            'create_order',
        ],
        'GET'  => [
            'retrieve_order',
            'abort_order',
        ],
        'PUT'  => [
            //
        ],
    ];

    /**
     * Defined API client method type
     * @var array
     */
    protected static $api_method_type = [
        'body'  => [
            'POST',
            'PUT',
        ],
        'query' => [
            'GET',
        ],
    ];

    /**
     * This are dynamically added headers for API call
     * @var array
     */
    public $headers;

    public $cron_stop = [
        'internal_error',
        'manual_review_required',
        'unauthorized_access',
        'account_locked',
        'account_locked_verification_required',
        'account_login_failed',
        'additional_information_required',
        'billing_address_refused',
        'brand_not_accepted',
        'credit_card_declined',
        'incomplete_account_setup',
        'insufficient_addax_balance',
        'invalid_card_number',
        'invalid_client_token',
        'invalid_expiration_date',
        'invalid_json',
        'invalid_login_credentials',
        'invalid_payment_method',
        'invalid_security_code',
        'invalid_shipping_method',
        'payment_info_problem',
        'shipping_address_refused',
        'shipping_address_unavailable',
        'shipping_method_unavailable',
    ];
}
