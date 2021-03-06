<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\DeliveryDateStockValidation\Api;

interface StockValidatorInterface
{
    /**
     * Check is product available in any stock
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return bool
     */
    public function isInStock(\Magento\Catalog\Api\Data\ProductInterface $product): bool;
}
