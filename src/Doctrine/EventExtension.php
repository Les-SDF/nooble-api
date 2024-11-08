<?php /** @noinspection PhpUnused */

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Event;
use App\Enum\EventStatus;
use App\Security\Roles;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * https://api-platform.com/docs/core/extensions/#custom-doctrine-orm-extension
 */
readonly class EventExtension implements QueryCollectionExtensionInterface
{
    public function __construct(private Security $security)
    {
    }

    /**
     * @inheritDoc
     */
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        if (Event::class !== $resourceClass) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $user = $this->security->getUser();

        if ($this->security->isGranted(Roles::ORGANISER)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->neq("$rootAlias.status", ':status'),
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq("$rootAlias.status", ':status'),
                        $queryBuilder->expr()->eq("$rootAlias.creator", ':creator')
                    )
                )
            )
                ->setParameter('status', EventStatus::Archived)
                ->setParameter('creator', $user);
        } else {
            $queryBuilder->andWhere($queryBuilder->expr()->neq("$rootAlias.status", ':status'))
                ->setParameter('status', EventStatus::Archived);
        }
    }
}
