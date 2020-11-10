<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\BlogCategory;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\Common\IteratorFactory;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\ChildCountUpdater;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexer;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexingMessage;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\TreeUpdater;
use Shopware\Core\Framework\Uuid\Uuid;

class BlogCategoryIndexer extends EntityIndexer
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EntityRepositoryInterface
     */
    private $repository;

    /**
     * @var ChildCountUpdater
     */
    private $childCountUpdater;

    /**
     * @var TreeUpdater
     */
    private $treeUpdater;

    /**
     * @var IteratorFactory
     */
    private $iteratorFactory;

    public function __construct(
        Connection $connection,
        IteratorFactory $iteratorFactory,
        EntityRepositoryInterface $repository,
        ChildCountUpdater $childCountUpdater,
        TreeUpdater $treeUpdater
    ) {
        $this->repository = $repository;
        $this->childCountUpdater = $childCountUpdater;
        $this->treeUpdater = $treeUpdater;
        $this->connection = $connection;
        $this->iteratorFactory = $iteratorFactory;
    }

    public function getName(): string
    {
        return 'sas.blog.category.indexer';
    }

    public function iterate($offset): ?EntityIndexingMessage
    {
        $iterator = $this->iteratorFactory->createIterator($this->repository->getDefinition(), $offset);

        $ids = $iterator->fetch();

        if (empty($ids)) {
            return null;
        }

        return new EntityIndexingMessage(array_values($ids), $iterator->getOffset());
    }

    public function update(EntityWrittenContainerEvent $event): ?EntityIndexingMessage
    {
        $categoryEvent = $event->getEventByEntityName(BlogCategoryDefinition::ENTITY_NAME);

        if (!$categoryEvent) {
            return null;
        }

        $ids = $categoryEvent->getIds();
        foreach ($categoryEvent->getWriteResults() as $result) {
            if (!$result->getExistence()) {
                continue;
            }
            $state = $result->getExistence()->getState();

            if (isset($state['parent_id'])) {
                $ids[] = Uuid::fromBytesToHex($state['parent_id']);
            }

            $payload = $result->getPayload();
            if (isset($payload['parentId'])) {
                $ids[] = $payload['parentId'];
            }
        }

        if (empty($ids)) {
            return null;
        }

        // tree should be updated immediately
        foreach ($ids as $id) {
            $this->treeUpdater->update($id, BlogCategoryDefinition::ENTITY_NAME, $event->getContext());
        }

        $children = $this->fetchChildren($ids, $event->getContext()->getVersionId());

        $ids = array_unique(array_merge($ids, $children));

        return new EntityIndexingMessage(array_values($ids), null, $event->getContext(), \count($ids) > 20);
    }

    public function handle(EntityIndexingMessage $message): void
    {
        $ids = $message->getData();

        $ids = array_unique(array_filter($ids));
        if (empty($ids)) {
            return;
        }

        $context = Context::createDefaultContext();

        $this->connection->beginTransaction();

        // listen to parent id changes
        $this->childCountUpdater->update(BlogCategoryDefinition::ENTITY_NAME, $ids, $context);

        foreach ($ids as $id) {
            // listen to parent id changes
            $this->treeUpdater->update($id, BlogCategoryDefinition::ENTITY_NAME, $context);
        }

        $this->connection->commit();
    }

    private function fetchChildren(array $categoryIds, string $versionId): array
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('DISTINCT LOWER(HEX(category.id))');
        $query->from('category');

        $wheres = [];
        foreach ($categoryIds as $id) {
            $key = 'path' . $id;
            $wheres[] = 'category.path LIKE :' . $key;
            $query->setParameter($key, '%|' . $id . '|%');
        }

        $query->andWhere('(' . implode(' OR ', $wheres) . ')');
        $query->andWhere('category.version_id = :version');
        $query->setParameter('version', Uuid::fromHexToBytes($versionId));

        return $query->execute()->fetchAll(\PDO::FETCH_COLUMN);
    }
}
