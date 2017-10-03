<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Routing;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Lcobucci\Chimera\QueryBus;
use Psr\Http\Message\ServerRequestInterface;

final class FetchOnly implements MiddlewareInterface
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var string
     */
    private $query;

    public function __construct(
        QueryBus $queryBus,
        string $query
    ) {
        $this->queryBus = $queryBus;
        $this->query    = $query;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $result = $this->queryBus->handle($this->query, $request);

        $request = $request->withAttribute(Attributes::QUERY_RESULT, $result)
                           ->withAttribute(Attributes::PROCESSED, true);

        return $handler->handle($request);
    }
}
