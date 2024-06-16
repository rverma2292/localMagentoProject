<?php

    namespace Mageplaza\HelloWorld\Controller\Index;

    use Magento\Framework\Controller\ResultFactory;

    class Uidemo extends \Magento\Framework\App\Action\Action
    {
        public function execute(){
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        }

    }
