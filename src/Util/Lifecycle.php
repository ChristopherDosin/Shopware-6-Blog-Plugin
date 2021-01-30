<?php declare(strict_types=1);
namespace Sas\BlogModule\Util;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class Lifecycle
{
    /**
     * @var SystemConfigService
     */
    private $systemConfig;

    /**
     * @var EntityRepositoryInterface
     */
    private $cmsPageRepository;

    public function __construct(
        SystemConfigService $systemConfig,
        EntityRepositoryInterface $cmsPageRepository
    ) {
        $this->systemConfig = $systemConfig;
        $this->cmsPageRepository = $cmsPageRepository;
    }

    public function install(Context $context): void
    {
        $this->createBlogCmsListingPage($context);
    }

    private function createBlogCmsListingPage(Context $context): void
    {
        $blogListingCmsPageId = Uuid::randomHex();
        $blogDetailCmsPageId = Uuid::randomHex();

        $cmsPage = [
            [
                'id' => $blogListingCmsPageId,
                'type' => 'page',
                'name' => 'Blog Listing',
                'sections' => [
                    [
                        'id' => Uuid::randomHex(),
                        'type' => 'default',
                        'position' => 0,
                        'blocks' => [
                            [
                                'position' => 1,
                                'type' => 'blog-listing',
                                'slots' => [
                                    ['type' => 'blog', 'slot' => 'listing'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => $blogDetailCmsPageId,
                'type' => 'page',
                'name' => 'Blog Detail',
                'sections' => [
                    [
                        'id' => Uuid::randomHex(),
                        'type' => 'default',
                        'position' => 0,
                        'blocks' => [
                            [
                                'position' => 1,
                                'type' => 'blog-detail',
                                'slots' => [
                                    ['type' => 'blog-detail', 'slot' => 'blogDetail'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $this->cmsPageRepository->create($cmsPage, $context);
        $this->systemConfig->set('SasBlogModule.config.cmsBlogDetailPage', $blogDetailCmsPageId);
    }
}
