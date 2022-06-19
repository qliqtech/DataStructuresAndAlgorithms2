<?php

namespace App\Http\Middleware;

use App\ImplementationService\AuditService;
use App\Models\Audit;
use Closure;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Support\Str;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       // dd($request);

        $requeststartime = now();
        $request->request_guid = Str::uuid();


        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Methods', 'HEAD, GET, PUT, PATCH, POST');


      //  $requestendtime

        $useragent = ['client_ip'=>$request->ip(),
            'guid'=>$request->request_guid,
            'request_url'=>$request->fullUrl(),
            'browser'=>$request->userAgent(),
            'response_body'=>json_encode($response)];

        $auditservice =  new AuditService();

        $auditservice->saveAudit($useragent);

        return $response;
    }
}
