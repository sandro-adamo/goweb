<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Config;

class Autenticacao
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
		
        $rota = explode('.', \Route::currentRouteName());

        $ip_addr = $_SERVER["REMOTE_ADDR"];

        $id_usuario = \Auth::id();

        if (Auth::check() && Auth::user()->status == 0) {

            \Auth::logout();
            return redirect('/login');

        }

        if ($id_usuario <> ''  && !$request->is('login')  ) {
            $uri = $_SERVER["REQUEST_URI"];

            $modulo = explode('/', $uri);
            $modulo = explode('?', $modulo[1]);

            $id_perfil = \Auth::user()->id_perfil;

            $routeArray = app('request')->route()->getAction();
            if (isset($routeArray['controller'])) {

                $controllerAction = $routeArray['controller'];
                $namespace = $routeArray['namespace'];
                $controllerAction = explode('@', $controllerAction);


                    $programa = preg_replace('/.*\\\/', '', $controllerAction[0]);
                    $funcao = $controllerAction[1];

                    $log = new \App\Log();
                    $log->id_usuario = \Auth::id();
                    $log->nome_usuario = \Auth::user()->nome;

                    $log->remote_addr = $request->ip();
                    $log->user_agent = $request->header('User-Agent');

                    $log->pathInfo = $request->getUri();
                    $log->requestUri = $request->route()->uri;

                    $log->route_path = $request->path();
                    $log->route_name = $request->route()->getName();
                    $log->route_action = $request->route()->getActionName();

                    $log->controller = $programa;
                    $log->function = $funcao;
                    $log->parametros = json_encode($request->all());
                    $log->aplicacao = 'portalrep';

                    $log->status = 'ACCEPT';
                    $log->save();
	}
//            $logs = \DB::select("insert into logs ( datahora, id_usuario, modulo, url, ip_addr, historico) values ( NOW(), $id_usuario, '$modulo[0]', '$uri', '$ip_addr', 'teste') ");
        }

        if (Auth::check()) {

            
            if (Auth::user()->id_perfil == 1  or Auth::user()->id_perfil == 25   ) {

                config(['app.debug' => true]);
    
            } else {
    
                //config(['app.debug' => true]);
				config(['app.debug' => false]);
    
            }
    

            \App::setLocale(\Auth::user()->lang);
           
        }
        

        if(!$request->is('login') && Auth::guest() && !$request->is('getProtocoloAtendimento') && !$request->is('addHistorico')) {


             return redirect('/login');
        } 

        return $next($request);
    }
}
