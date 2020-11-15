<?php declare(strict_types=1);
namespace Sas\BlogModule;

use Doctrine\DBAL\Connection;
use Sas\BlogModule\Content\Blog\BlogEntriesDefinition;
use Sas\BlogModule\Util\Lifecycle;
use Sas\BlogModule\Util\Update;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class SasBlogModule extends Plugin
{
    public const ANONYMOUS_AUTHOR_ID = '64f4c60194634128b9b85d9299797c45';

    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);

        $this->createBlogMediaFolder($installContext->getContext());

        $this->getLifeCycle()->install($installContext->getContext());
    }

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }

        /**
         * We need to uninstall our default media folder,
         * the media folder and the thumbnail sizes.
         * However, we have to clean this up within a next update :)
         */
        $this->deleteMediaFolder($context->getContext());
        $this->deleteDefaultMediaFolder($context->getContext());
        $this->deleteSeoUrlTemplate($context->getContext());
        $this->checkForThumbnailSizes($context->getContext());

        /**
         * And of course we need to drop our tables
         */
        $connection = $this->container->get(Connection::class);

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0;');
        $connection->executeQuery('DROP TABLE IF EXISTS `sas_blog_entries`');
        $connection->executeQuery('DROP TABLE IF EXISTS `sas_blog_entries_translation`');
        $connection->executeQuery('DROP TABLE IF EXISTS `sas_blog_blog_category`');
        $connection->executeQuery('DROP TABLE IF EXISTS `sas_blog_category_translation`');
        $connection->executeQuery('DROP TABLE IF EXISTS `sas_blog_category`');
        $connection->executeQuery('DROP TABLE IF EXISTS `sas_blog_author_translation`');
        $connection->executeQuery('DROP TABLE IF EXISTS `sas_blog_author`');

        /** @var EntityRepositoryInterface $cmsBlockRepo */
        $cmsBlockRepo = $this->container->get('cms_block.repository');

        $context = Context::createDefaultContext();

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsAnyFilter('type', ['blog-detail', 'blog-listing']));

        $cmsBlocks = $cmsBlockRepo->searchIds($criteria, $context);

        $cmsBlockRepo->delete(array_values($cmsBlocks->getData()), $context);

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function update(UpdateContext $updateContext): void
    {
        parent::update($updateContext);

        (new Update())->update($this->container, $updateContext);

        if (\version_compare($updateContext->getCurrentPluginVersion(), '1.1.0', '<')) {
            $this->createBlogMediaFolder($updateContext->getContext());
        }
    }

    /**
     * We need to create a folder for the blog media with it's,
     * own configuration to generate thumbnails for the teaser image.
     *
     * @param Context $context
     */
    public function createBlogMediaFolder(Context $context): void
    {
        $this->deleteDefaultMediaFolder($context);
        $this->checkForThumbnailSizes($context);

        /** @var EntityRepositoryInterface $mediaFolderRepository */
        $mediaFolderRepository = $this->container->get('media_default_folder.repository');

        $mediaFolderRepository->create([
            [
                'entity'            => BlogEntriesDefinition::ENTITY_NAME,
                'associationFields' => ['media'],
                'folder'            => [
                    'name'                   => 'Blog Images',
                    'useParentConfiguration' => false,
                    'configuration'          =>
                        [
                            'createThumbnails'    => true,
                            'mediaThumbnailSizes' => [
                                [
                                    'width'  => 650,
                                    'height' => 330,
                                ],
                            ],
                        ],
                ],
            ],
        ], $context);
    }

    private function deleteDefaultMediaFolder(Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsAnyFilter('entity', [
                BlogEntriesDefinition::ENTITY_NAME,
            ])
        );

        /** @var EntityRepositoryInterface $mediaFolderRepository */
        $mediaFolderRepository = $this->container->get('media_default_folder.repository');

        $mediaFolderIds = $mediaFolderRepository->searchIds($criteria, $context)->getIds();

        if (!empty($mediaFolderIds)) {
            $ids = array_map(static function ($id) {
                return ['id' => $id];
            }, $mediaFolderIds);
            $mediaFolderRepository->delete($ids, $context);
        }
    }

    private function deleteMediaFolder(Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('name', 'Blog Images')
        );

        /** @var EntityRepositoryInterface $mediaFolderRepository */
        $mediaFolderRepository = $this->container->get('media_folder.repository');

        $mediaFolderRepository->search($criteria, $context);

        $mediaFolderIds = $mediaFolderRepository->searchIds($criteria, $context)->getIds();

        if (!empty($mediaFolderIds)) {
            $ids = array_map(static function ($id) {
                return ['id' => $id];
            }, $mediaFolderIds);
            $mediaFolderRepository->delete($ids, $context);
        }
    }

    private function deleteSeoUrlTemplate(Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('entityName', 'sas_blog_entries')
        );

        /** @var EntityRepositoryInterface $seoUrlTemplateRepository */
        $seoUrlTemplateRepository = $this->container->get('seo_url_template.repository');

        $seoUrlTemplateRepository->search($criteria, $context);

        $seoUrlTemplateIds = $seoUrlTemplateRepository->searchIds($criteria, $context)->getIds();

        if (!empty($seoUrlTemplateIds)) {
            $ids = array_map(static function ($id) {
                return ['id' => $id];
            }, $seoUrlTemplateIds);
            $seoUrlTemplateRepository->delete($ids, $context);
        }
    }

    private function checkForThumbnailSizes(Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new MultiFilter(
                MultiFilter::CONNECTION_AND,
                [
                    new EqualsFilter('width', 650),
                    new EqualsFilter('height', 330),
                ]
            )
        );

        /** @var EntityRepositoryInterface $thumbnailSizeRepository */
        $thumbnailSizeRepository = $this->container->get('media_thumbnail_size.repository');

        $thumbnailIds = $thumbnailSizeRepository->searchIds($criteria, $context)->getIds();

        if (!empty($thumbnailIds)) {
            $ids = array_map(static function ($id) {
                return ['id' => $id];
            }, $thumbnailIds);
            $thumbnailSizeRepository->delete($ids, $context);
        }
    }

    private function getLifeCycle(): Lifecycle
    {
        /** @var SystemConfigService $systemConfig */
        $systemConfig = $this->container->get(SystemConfigService::class);

        /** @var EntityRepositoryInterface $cmsPageRepository */
        $cmsPageRepository = $this->container->get('cms_page.repository');

        return new Lifecycle(
            $systemConfig,
            $cmsPageRepository
        );
    }
}
