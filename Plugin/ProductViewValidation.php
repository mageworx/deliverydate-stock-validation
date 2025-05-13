<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\DeliveryDateStockValidation\Plugin;

use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;

class ProductViewValidation
{
    /**
     * @var \MageWorx\DeliveryDateStockValidation\Api\StockValidatorInterface
     */
    private $stockValidator;

    /**
     * @var \MageWorx\DeliveryDate\Helper\Data
     */
    private $mainHelper;

    /**
     * ProductViewValidation constructor.
     *
     * @param \MageWorx\DeliveryDateStockValidation\Api\StockValidatorInterface $stockValidator
     * @param \MageWorx\DeliveryDate\Helper\Data $mainHelper
     */
    public function __construct(
        \MageWorx\DeliveryDateStockValidation\Api\StockValidatorInterface $stockValidator,
        \MageWorx\DeliveryDate\Helper\Data $mainHelper
    ) {
        $this->stockValidator = $stockValidator;
        $this->mainHelper = $mainHelper;
    }

    public function afterIsAllowedByProduct(
        \MageWorx\DeliveryDate\Block\Product\View\EstimatedDeliveryTime $subject,
        bool $result,
        ?\Magento\Catalog\Api\Data\ProductInterface $product = null
    ) {
        if (!$result) {
            // Do not check for products for which delivery date is already unavailable
            return $result;
        }

        $product = $product ?? $subject->getProduct();

        $inStock = true;

        if ($this->mainHelper->isUseStock() && $product->getTypeId() === 'simple') {
            $inStock = $this->stockValidator->isInStock($product);
        }

        return $inStock;
    }
}
