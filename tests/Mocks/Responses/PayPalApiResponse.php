<?php

namespace Tests\Mocks\Responses;

class PayPalApiResponse
{
    public static function token(): array
    {
        return [
            'scope' => 'https://uri.paypal.com/services/invoicing https://uri.paypal.com/services/disputes/read-buyer https://uri.paypal.com/services/payments/realtimepayment https://uri.paypal.com/services/disputes/update-seller https://uri.paypal.com/services/payments/payment/authcapture openid https://uri.paypal.com/services/disputes/read-seller https://uri.paypal.com/services/payments/refund https://api.paypal.com/v1/vault/credit-card https://api.paypal.com/v1/payments/.* https://uri.paypal.com/payments/payouts https://api.paypal.com/v1/vault/credit-card/.* https://uri.paypal.com/services/subscriptions https://uri.paypal.com/services/applications/webhooks',
            'access_token' => 'A21AAK0bqGokMIxVEU2O-x9a04BG0xX6-geO6JmogaA0J3lCHqLKhKWvLWT2NtkP1VUOuWGBsfx3PwiHwBAhwb5UN80TmM65w',
            'token_type' => 'Bearer',
            'app_id' => 'APP-80W284485P519543T',
            'expires_in' => 32358,
            'nonce' => '2020-12-01T00:49:57ZSvHY0k14KHSXBV-6Al4jAhQ-_e5wPsZdAfJuneW911U',
        ];
    }
    
    public static function failureAuthentication(): array
    {
        return [
            'name' => 'AUTHENTICATION_FAILURE',
            'message' => 'Authentication failed due to invalid authentication credentials or a missing Authorization header.',
            'links' => [
                [
                    'href' => 'https://developer.paypal.com/docs/api/overview/#error',
                    'rel' => 'information_link'
                ]
            ]
        ];
    }

    public static function missingGrantType(): array
    {
        return [
            'error' => 'unsupported_grant_type',
            'error_description' => 'Grant Type is NULL',
        ];
    }

    public static function unsupportedGrantType(): array
    {
        return [
            'error' => 'unsupported_grant_type',
            'error_description' => 'unsupported grant_type',
        ];
    }

    public static function invalidToken(): array
    {
        return [
            'error' => 'invalid_token',
            'error_description' => 'Authorization header does not have valid access token',
        ];
    }
}
