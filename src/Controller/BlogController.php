<?php declare(strict_types=1);
namespace Sas\BlogModule\Controller;

use Sas\BlogModule\Content\Blog\BlogEntriesEntity;
use Shopware\Core\Content\Cms\Exception\PageNotFoundException;
use Shopware\Core\Content\Cms\SalesChannel\SalesChannelCmsPageLoaderInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Framework\Cache\Annotation\HttpCache;
use Shopware\Storefront\Page\GenericPageLoader;
use Shopware\Storefront\Page\Navigation\NavigationPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"storefront"})
 */
class BlogController extends StorefrontController
{
    /**
     * @var GenericPageLoader
     */
    private $genericPageLoader;
    /**
     * @var SalesChannelCmsPageLoaderInterface
     */
    private $cmsPageLoader;

    /**
     * @var SystemConfigService
     */
    private $systemConfigService;

    public function __construct(SystemConfigService $systemConfigService, GenericPageLoader $genericPageLoader, SalesChannelCmsPageLoaderInterface $cmsPageLoader)
    {
        $this->systemConfigService = $systemConfigService;
        $this->genericPageLoader = $genericPageLoader;
        $this->cmsPageLoader = $cmsPageLoader;
    }

    /**
     * @HttpCache()
     * @Route("/sas_blog/{articleId}", name="sas.frontend.blog.detail", methods={"GET"})
     */
    public function detailAction(string $articleId, Request $request, SalesChannelContext $context): Response
    {
        $page = $this->genericPageLoader->load($request, $context);
        $page = NavigationPage::createFrom($page);

        /** @var EntityRepositoryInterface $blogRepository */
        $blogRepository = $this->container->get('sas_blog_entries.repository');

        $criteria = new Criteria([$articleId]);

        $criteria->addAssociations(['author.salutation', 'blogCategories']);

        $results = $blogRepository->search($criteria, $context->getContext())->getEntities();

        /** @var BlogEntriesEntity $entry */
        $entry = $results->first();

        if (!$entry) {
            throw new PageNotFoundException($articleId);
        }

        $pages = $this->cmsPageLoader->load(
            $request,
            new Criteria([$this->systemConfigService->get('BlogModule.config.cmsBlogDetailPage')]),
            $context
        );

        $page->setCmsPage($pages->first());
        $metaInformation = $page->getMetaInformation();

        $metaInformation->setAuthor($entry->getAuthor()->getTranslated()['name']);

        $page->setMetaInformation($metaInformation);

        return $this->renderStorefront('@Storefront/storefront/page/content/index.html.twig', [
            'page'  => $page,
            'entry' => $entry,
        ]);
    }
}
