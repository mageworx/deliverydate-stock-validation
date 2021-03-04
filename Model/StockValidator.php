<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace MageWorx\DeliveryDateStockValidation\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\InventoryConfigurationApi\Api\GetStockItemConfigurationInterface;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Magento\InventorySalesApi\Api\StockResolverInterface;
use Magento\Store\Model\StoreManagerInterface;
use MageWorx\DeliveryDateStockValidation\Api\StockValidatorInterface;

class StockValidator implements StockValidatorInterface
{
    /**
     * @var GetStockItemConfigurationInterface
     */
    private $getStockItemConfiguration;

    /**
     * @var GetProductSalableQtyInterface
     */
    private $productSalableQty;

    /**
     * @var StockResolverInterface
     */
    private $stockResolver;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * StockValidator constructor.
     *
     * @param GetProductSalableQtyInterface $productSalableQty
     * @param GetStockItemConfigurationInterface $getStockItemConfiguration
     * @param StockResolverInterface $stockResolver
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        GetProductSalableQtyInterface $productSalableQty,
        GetStockItemConfigurationInterface $getStockItemConfiguration,
        StockResolverInterface $stockResolver,
        StoreManagerInterface $storeManager
    ) {
        $this->getStockItemConfiguration = $getStockItemConfiguration;
        $this->productSalableQty         = $productSalableQty;
        $this->stockResolver             = $stockResolver;
        $this->storeManager              = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function isInStock(ProductInterface $product): bool
    {
        try {
            $websiteCode = $this->storeManager->getWebsite()->getCode();
            $stock       = $this->stockResolver->execute(SalesChannelInterface::TYPE_WEBSITE, $websiteCode);
            $stockId     = $stock->getStockId();

            $stockItem = $this->getStockItemConfiguration->execute($product->getSku(), $stockId);
            $minQty    = $stockItem->getMinQty();

            $salableQty = $this->productSalableQty->execute($product->getSku(), $stockId);

            if ($salableQty >= $minQty && $salableQty > 0) {
                $isInStock = true;
            } else {
                $isInStock = false;
            }
        } catch (InputException | LocalizedException $exception) {
            $isInStock = false;
        }

        return $isInStock;
    }
}
