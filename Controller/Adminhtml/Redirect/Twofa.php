<?php
namespace MiniOrange\SecuritySuite\Controller\Adminhtml\Redirect;

class Twofa extends \Magento\Backend\App\Action
{
    protected $moduleManager;
    protected $backendUrl;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Backend\Model\UrlInterface $backendUrl
    ) {
        parent::__construct($context);
        $this->moduleManager = $moduleManager;
        $this->backendUrl = $backendUrl;
    }

    public function execute()
    {
        // Check if MiniOrange TwoFA module is installed and enabled
        if ($this->moduleManager->isEnabled('MiniOrange_TwoFA')) {
            // Generate the correct URL to MiniOrange's account page
            $miniorangeUrl = $this->backendUrl->getUrl('motwofa/account/index');

//            var_dump($miniorangeUrl);exit;

            // Redirect to the generated URL (will include the proper security key)
            return $this->_redirect($miniorangeUrl);
        } else {
            // Fallback if 2FA module not available
            $this->messageManager->addError(__('Two-Factor Authentication module is not installed.'));
            return $this->_redirect('adminhtml/dashboard/index');
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MiniOrange_SecuritySuite::twofactor');
    }
}
