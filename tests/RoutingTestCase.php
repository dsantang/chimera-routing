<?php
declare(strict_types=1);

namespace Lcobucci\Chimera\Routing\Tests;

use Interop\Http\Server\RequestHandlerInterface;
use Lcobucci\Chimera\CommandBus;
use Lcobucci\Chimera\QueryBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class RoutingTestCase extends \PHPUnit\Framework\TestCase
{
    protected function createCommandBus(
        ServerRequestInterface $request,
        string $expectedCommand
    ): CommandBus {
        $bus = $this->createMock(CommandBus::class);

        $bus->expects($this->once())
            ->method('handle')
            ->with($expectedCommand, $this->equalTo($request));

        return $bus;
    }

    protected function createQueryBus(
        ServerRequestInterface $request,
        string $expectedQuery,
        $result
    ): QueryBus {
        $bus = $this->createMock(QueryBus::class);

        $bus->expects($this->once())
            ->method('handle')
            ->with($expectedQuery, $this->equalTo($request))
            ->willReturn($result);

        return $bus;
    }

    protected function createRequestHandler(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): RequestHandlerInterface {
        $handler = $this->createMock(RequestHandlerInterface::class);

        $handler->expects($this->once())
                ->method('handle')
                ->with($this->equalTo($request))
                ->willReturn($response);

        return $handler;
    }
}
