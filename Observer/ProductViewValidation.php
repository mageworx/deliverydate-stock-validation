<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\DeliveryDateStockValidation\Observer;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProduct;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MageWorx\DeliveryDate\Exceptions\DeliveryTimeException;

class ProductViewValidation implements ObserverInterface
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
        $this->mainHelper     = $mainHelper;
    }

    /**
     * @inheritDoc
     * @throws DeliveryTimeException
     */
    public function execute(Observer $observer)
    {
        $quote = $observer->getData('quote');
        if (!$quote || !$quote instanceof \Magento\Quote\Model\Quote) {
            return;
        }

        if (!$this->mainHelper->isUseStock($quote->getStoreId())) {
            return;
        }

        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($quote->getAllVisibleItems() as $item) {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $item->getProduct();
            if ($product->getTypeId() === ConfigurableProduct::TYPE_CODE) {
                $children = $item->getChildren();
                if (!empty($children) && is_array($children)) {
                    $childQuoteItem = current($children);
                    $product        = $childQuoteItem->getProduct();
                }
            }

            $isSalable = $this->stockValidator->isInStock($product);
            if (!$isSalable) {
                throw new DeliveryTimeException();
            }
        }
    }
}
