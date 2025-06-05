<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CustomPaginationExtension implements QueryCollectionExtensionInterface
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = []
    ): void {
        $queryBuilder->setFirstResult($this->getFirstResult());
        $queryBuilder->setMaxResults($this->getMaxResults());
    }

    private function getFirstResult(): ?int
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();
        /** @var int $page */
        $page = $request->get('page', 0);
        /** @var int $limit */
        $limit = $request->get('limit', 0);

        return $page * $limit > 0 ? ($page - 1) * $limit : null;
    }

    private function getMaxResults(): ?int
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();
        $limit = $request->get('limit');

        return is_numeric($limit) ? (int) $limit : null;
    }
}
