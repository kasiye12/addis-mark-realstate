<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AgentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check if user is admin or agent
        if (!$user->isAdmin() && !$user->isAgent()) {
            abort(403, 'Unauthorized. Agent access required.');
        }

        return $next($request);
    }
}