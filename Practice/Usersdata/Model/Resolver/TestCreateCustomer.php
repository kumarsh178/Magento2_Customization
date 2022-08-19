<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Practice\Usersdata\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
/**
 * Customer is_subscribed field resolver
 */
class TestCreateCustomer implements ResolverInterface
{

    private $_customerFactory;

    public function __construct(\Magento\Customer\Model\CustomerFactory $customerFactory){
            $this->_customerFactory = $customerFactory;
    }
    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        
        $output =array();
        $args = $args['input'];
        $firstName = $args['firstname'];
        $lastName = $args['lastname'];
        $email = $args['email'];
        $password = $args['password'];
        $is_subscribed = $args['is_subscribed'];

        // instantiate customer object
        $customer = $this->_customerFactory->create();
        $customer->setWebsiteId(1);
        
        // check if customer is already present
        // if customer is already present, then show error message
        // else create new customer
        if ($customer->loadByEmail($email)->getId()) {
            //echo 'Customer with the email ' . $email . ' is already registered.';
            $message = __(
                'There is already an account with this email address "%1".',
                $email
            );
            $output['error_message'] = null;
            $output['success_message'] = $message;
        } else {
            try {
                // prepare customer data
                $customer->setEmail($email); 
                $customer->setFirstname($firstName);
                $customer->setLastname($lastName);

                // set null to auto-generate password
                $customer->setPassword($password); 

                // set the customer as confirmed
                // this is optional
                // comment out this line if you want to send confirmation email
                // to customer before finalizing his/her account creation
                $customer->setForceConfirmed(true);
                
                // save data
                $customer->save();
                $output['error_message'] = null;
                $output['success_message'] = 'Customer created successfully';
                return $output;
            }catch(Exception $e){
                throw new GraphQlNoSuchEntityException(__($e->getMessage()));
            }
        }

       /* if(!isset($args['email']) || empty($args['email'])){
                throw new GraphQlAuthorizationException(__('Email for customer should be specified',[\Magento\Customer\Model\Customer::ENTITY]));
        }
        try{
            return $this->getCustomerData($args['email']);
        }catch(NoSuchEntityException $e){
            throw new GraphQlNoSuchEntityException(__($e->getMessage()));
        }catch(LocalizedException $e){
            throw new GraphQlNoSuchEntityException(__($e->getMessage()));
        }
        */
        return $output;
    }
}
