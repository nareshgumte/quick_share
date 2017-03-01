<?php

return [
    'nonceHeaderKey' => 'x-qs-nonce',
    'signatureHeaderKey' => 'x-qs-signature',
    'accessTokenHeaderKey' => 'x-qs-authentication-key',
    'publicKey' => 'test',
    'useSignature' => false,
    'preventDuplicates' => false,
    'cacheValueDuration' => 20, /* seconds */
    'passwordResetTokenExpire' => 86400,
    'passwordHistory' => 3,
    'apiRequestKeys' => ['attributes'],
];
