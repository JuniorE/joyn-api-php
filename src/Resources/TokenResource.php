<?php
    /**
     * Created by PhpStorm.
     * User: JuniorE.
     * Date: 2019-06-26
     * Time: 11:44
     */

    namespace JuniorE\JoynApiClient\Resources;

    use JuniorE\JoynApiClient\JoynApiClient;

    class TokenResource extends BaseResource
    {
        public $transactionReference;
        public $transactionTimestamp;
        public $points;
        public $amount;
        public $expirationDate;
        public $token;
        public $status;
        public $source;
        public $shopName;
        public $qrCodeUrl;
        public $badge;
        public $tokenUrl;
        public $validityPeriod;
        public $lastModifiedDate;
        public $createdDate;

        public function isCreated()
        {
            return $this->status === 'CREATED';
        }

    }
