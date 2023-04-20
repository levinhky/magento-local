<?php
namespace Smartosc\MultipleFilterGrid\Model;

class ProductDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    /**
     * @inheritdoc
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if (isset($this->addFilterStrategies[$filter->getField()]))
        {
            $this->addFilterStrategies[$filter->getField()]
                ->addFilter(
                    $this->getCollection(),
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
        }
        elseif ($filter->getField() == "name" || $filter->getField() == "sku"  && count(explode(",",str_replace("%","",$filter->getValue()))) > 1)
        {
            $withComma = explode(",",str_replace("%","",$filter->getValue()));

            $attrs = array();
            foreach ($withComma as $cItem)
            {
                $attrs[] = ['attribute' => $filter->getField(), $filter->getConditionType() => '%'.trim($cItem).'%'];
            }
            $this->getCollection()->addAttributeToFilter($attrs);
        }
        else
        {
            parent::addFilter($filter);
        }
    }
}
