<?php

namespace Vynyl\Campaigner\Responses;

use Vynyl\Campaigner\DTO\ProductCategory;

class ProductCategoryResponse extends CampaignerResponse
{
    private $productCategory;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getProductCategory()
    {
        return $this->productCategory;
    }

    /**
     * @param mixed $product
     * @return ProductResponse
     */
    public function setProductCategory(ProductCategory $product)
    {
        $this->productCategory = $product;
        return $this;
    }

}
