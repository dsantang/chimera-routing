<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Routing;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Lcobucci\Chimera\CommandBus;
use Psr\Http\Message\ServerRequestInterface;

final class CreateOnly implements MiddlewareInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var string
     */
    private $command;

    /**
     * @var callable
     */
    private $idGenerator;

    /**
     * @var string
     */
    private $routeName;

    public function __construct(
        CommandBus $commandBus,
        string $command,
        callable $idGenerator,
        string $routeName
    ) {
        $this->commandBus  = $commandBus;
        $this->command     = $command;
        $this->idGenerator = $idGenerator;
        $this->routeName   = $routeName;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $request = $request->withAttribute(Attributes::GENERATED_ID, ($this->idGenerator)())
                           ->withAttribute(Attributes::RESOURCE_LOCATION, $this->routeName);

        $this->commandBus->handle($this->command, $request);

        return $handler->handle(
            $request->withAttribute(Attributes::PROCESSED, true)
        );
    }
}
