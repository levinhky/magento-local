<?php

namespace Smartosc\MultipleFilterGrid\Plugin\Magento\CatalogSearch\Ui\DataProvider\Product;

class ProductDataProvider
{
    /**
     * @inheritdoc
     */
    public function aroundAddFilter(
        \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider $subject,
        callable                                                     $proceed,
        \Magento\Framework\Api\Filter                                $filter
    )
    {
        if (isset($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]
                ->addFilter(
                    $this->getCollection(),
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
        } elseif ($filter->getField() == "sku" && count(explode(",", str_replace("%", "", $filter->getValue()))) > 1) {
            $withComma = explode(",", str_replace("%", "", $filter->getValue()));

            $attrs = array();
            foreach ($withComma as $cItem) {
                $attrs[] = ['attribute' => $filter->getField(), $filter->getConditionType() => '%' . trim($cItem) . '%'];
            }
            $this->getCollection()->addAttributeToFilter($attrs);
        } else {
            return $proceed($filter);
        }
    }
}
