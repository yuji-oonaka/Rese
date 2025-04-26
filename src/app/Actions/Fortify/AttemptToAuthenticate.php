<?php

namespace App\Actions\Fortify;

use App\Http\Requests\LoginRequest;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\LoginRateLimiter;

class AttemptToAuthenticate
{
    protected $guard;
    protected $limiter;

    public function __construct(StatefulGuard $guard, LoginRateLimiter $limiter)
    {
        $this->guard = $guard;
        $this->limiter = $limiter;
    }

    public function handle(LoginRequest $request, $next)
    {
        if ($this->guard->attempt(
            $request->only('email', 'password'),
            $request->filled('remember')
        )) {
            return $next($request);
        }

        $this->fireFailedEvent($request);

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    protected function fireFailedEvent($request)
    {
        event(new Failed(config('fortify.guard'), null, [
            'email' => $request->email,
            'password' => $request->password,
        ]));
    }
}