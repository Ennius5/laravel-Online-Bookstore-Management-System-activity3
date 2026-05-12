<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiRequestLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Log;

class LogApiRequest
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('LogApiRequest hit: ' . $request->path());
        // Attempt to get a normal response
        try {
            $response = $next($request);
        } catch (ThrottleRequestsException $e) {
            // Log the rate‑limited request
            $this->logRequest($request, 429, true);
            throw $e;   // re‑throw so Laravel can still return the 429 JSON
        }

        // Log successful / other HTTP responses
        $this->logRequest(
            $request,
            method_exists($response, 'getStatusCode') ? $response->getStatusCode() : 200,
            $response->getStatusCode() == 429   // unlikely, but safe
        );

        return $response;
    }

    private function logRequest(Request $request, int $statusCode, bool $rateLimited): void
    {
        if ($request->is('_debugbar*', '_ignition*')) {
            return;
        }

        try {
            ApiRequestLog::create([
                'user_id'      => Auth::id(),
                'endpoint'     => $request->path(),
                'status_code'  => $statusCode,
                'rate_limited' => $rateLimited,
            ]);
        } catch (\Exception $e) {
            Log::error('ApiRequestLog insertion failed: ' . $e->getMessage());
        }
    }
}
