<?php
return array(
    // set your paypal credential
    'client_id' => 'AZ-Q1YmD0SifOpqfK0eCpcBYa3Jas84U558LZdtDu_wBDnYGPf7iromXvSIfrX8X4y-oPDo_1s8pm7oV',
    'secret' => 'ENRSgeXn5ZzyNiV-ONi1uqc0v3xHRF7pqmnSiktirmGYRkUMF_In9vRwIwa4mbg3HoA_sAUYE_ti3Qzv',

    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',

        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 100,

        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,

        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',

        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);
