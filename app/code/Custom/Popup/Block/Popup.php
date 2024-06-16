<?php
    namespace Custom\Popup\Block;

    use Magento\Framework\View\Element\Template;
    use Magento\Framework\View\Element\Template\Context;

    class Popup extends Template
    {
        protected $_loginForm;
        protected $_registerForm;

        public function __construct(
            Context $context,
            \Magento\Customer\Block\Form\Login $loginForm,
            \Magento\Customer\Block\Form\Register $registerForm,
            array $data = []
        ) {
            $this->_loginForm = $loginForm;
            $this->_registerForm = $registerForm;
            parent::__construct($context, $data);
        }

        protected function _prepareLayout()
        {
            $this->setChild('login_form', $this->_loginForm->setTemplate('Magento_Customer::form/login.phtml'));
            $this->setChild('register_form', $this->_registerForm->setTemplate('Magento_Customer::form/register.phtml'));
            return parent::_prepareLayout();
        }
    }
