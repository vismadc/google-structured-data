<?php
namespace MageSuite\GoogleStructuredData\Plugin;

class AddBreadcrumbsToDataProvider
{
    /**
     * @var \MageSuite\GoogleStructuredData\Provider\StructuredDataContainer
     */
    private $structuredDataContainer;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \MageSuite\GoogleStructuredData\Provider\Data\Breadcrumbs
     */
    private $breadcrumbsDataProvider;

    public function __construct(
        \MageSuite\GoogleStructuredData\Provider\StructuredDataContainer $structuredDataContainer,
        \Psr\Log\LoggerInterface $logger,
        \MageSuite\GoogleStructuredData\Provider\Data\Breadcrumbs $breadcrumbsDataProvider
    )
    {
        $this->structuredDataContainer = $structuredDataContainer;
        $this->logger = $logger;
        $this->breadcrumbsDataProvider = $breadcrumbsDataProvider;
    }

    public function aroundAssign(\Magento\Framework\View\Element\Template $subject, callable $proceed, $key = '', $index = null)
    {
        if ($key == 'crumbs') {
            $this->addBreadcrumbsToProvider($index);
        }
        return $proceed($key, $index);
    }

    public function addBreadcrumbsToProvider($breadcrumbs)
    {
        try {
            $breadcrumbData = $this->breadcrumbsDataProvider->getBreadcrumbsData($breadcrumbs);

            $structuredData = $this->structuredDataContainer;

            $structuredData->add($breadcrumbData, $structuredData::BREADCRUMBS);

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
