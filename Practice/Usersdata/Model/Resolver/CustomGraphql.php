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

/**
 * Customer is_subscribed field resolver
 */
class CustomGraphql implements ResolverInterface
{

    
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
        if(!isset($args['username']) || !isset($args['password']) ||  !isset($args['fieldtype']) || empty($args['username']) || empty($args['password']) || empty($args['fieldtype'])){
                throw new GraphQlInputException(__('Invalid Parameter list'));
        }
        $output = array();
        $output['username'] = "Shailendra";
        $output['password'] = "abdfghtqe";
        $output['fieldtype'] = "usersdata";
        $output['defaultfields'] = json_encode($output);
        return $output;
    }
}
