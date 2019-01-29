<?php 

    require __DIR__ . '/../vendor/autoload.php';
        
    use Embryo\Http\Emitter\Emitter;
    use Embryo\Http\Server\RequestHandler;
    use Embryo\Http\Factory\{ServerRequestFactory, ResponseFactory};
    use Embryo\Session\Session;
    use Embryo\Session\Middleware\SessionMiddleware;
    use Embryo\Translate\Translate;
    use Embryo\Translate\Middleware\SetLocaleMiddleware;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Server\RequestHandlerInterface;
    
    $request    = (new ServerRequestFactory)->createServerRequestFromServer();
    $response   = (new ResponseFactory)->createResponse(200);
    $middleware = new RequestHandler;
    $session    = new Session;
    $emitter    = new Emitter;
    
    class TestTranslateMiddleware implements MiddlewareInterface
    {
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            $translate = new Translate(__DIR__.DIRECTORY_SEPARATOR.'lang');
            $translate->setMessages();
            $session   = $request->getAttribute('session');
            $messages  = $translate->getMessages($session->get('language'));
            $response  = $handler->handle($request);
            return $response->write('<p>'.$messages->get('hello').', '.$messages->get('name', ['name' => 'David']));
        }
    }

    $middleware->add(
        (new SessionMiddleware)
            ->setSession($session)
            ->setOptions([
                'use_cookies'      => false,
                'use_only_cookies' => true
            ])
    );

    $middleware->add(SetLocaleMiddleware::class);
    $middleware->add(TestTranslateMiddleware::class);
    $response = $middleware->dispatch($request, $response);
    
    $emitter->emit($response);