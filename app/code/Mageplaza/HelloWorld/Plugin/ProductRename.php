<?php

    namespace Mageplaza\HelloWorld\Plugin;

    class ProductRename
    {
//        public function afterGetName(\Magento\Catalog\Model\Product $product, $name){
//            $price = $product->getData('price');
//            if ($price >= 70) {
//                $name .= " so expensive";
//            } else{
//                $name .= " so chief";
//            }
//            return $name;
//        }

        public function aroundGetName(\Magento\Catalog\Model\Product $product, callable $proceed){
            $price = $product->getData('price');
//            if ($price >= 70) {
//                $name .= " so expensive";
//            }
            $result = $proceed();
            if ($price <= 70) {
                $result .= " wow so cheif!!";
            }

            return $result;
        }
    }
