<?php

namespace App\Providers;

use App\Domain\Auth\AuthContext;
use App\Domain\Auth\Authenticator;
use App\Domain\Auth\PinAuthenticator;
use App\Domain\Repositories\User\UserRepository;
use App\Gateway\Contracts\RequestForwarder;
use App\Gateway\Contracts\RouteResolver;
use App\Gateway\Contracts\ServiceRegistry;
use App\Gateway\Http\MtbxHttpRequestForwarder;
use App\Gateway\Routing\ConfigRouteResolver;
use App\Gateway\Routing\ConfigServiceRegistry;
use App\Infrastructure\Auth\MetaBoxAuthenticator;
use App\Infrastructure\Auth\MobilePinAuthenticator;
use App\Infrastructure\Auth\MtbxAuthContext;
use App\Infrastructure\Persistence\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class MtbxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Domain bindings
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(Authenticator::class, MetaBoxAuthenticator::class);
        $this->app->bind(AuthContext::class, MtbxAuthContext::class);

        // Mobile auth bindings
        $this->app->bind(PinAuthenticator::class, MobilePinAuthenticator::class);

        // Gateway bindings
        $this->app->bind(ServiceRegistry::class, ConfigServiceRegistry::class);
        $this->app->bind(RouteResolver::class, ConfigRouteResolver::class);
        $this->app->bind(RequestForwarder::class, MtbxHttpRequestForwarder::class);
    }

}
