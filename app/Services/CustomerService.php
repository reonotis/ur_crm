<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\CustomerRepository;

/**
 *
 */
class CustomerService
{
    /** @var CustomerRepository $customerRepository */
    private $customerRepository;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->customerRepository = app(CustomerRepository::class);
    }
}
