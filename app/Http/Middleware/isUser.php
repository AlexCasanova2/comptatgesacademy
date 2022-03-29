<?php

namespace App\Http\Middleware;

//Clases para sentinel
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Database\Capsule\Manager as Capsule;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

use Closure;

class isUser
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
      //obtenemos el usuario actual
      $user = Sentinel::getUser();
      //si no existe, nos vamos a login
      if(is_null($user)) return redirect('/');
      //si existe, pero no es ni entrenador, ni embajador, ni cliente, ni empleado, nos vamos a logout, por listo
      if(!Sentinel::inRole('entrenador') && !Sentinel::inRole('embajador') && !Sentinel::inRole('cliente') && !Sentinel::inRole('empleado') )
        return redirect('/logout');
      //Sino nos vamos palante
      return $next($request);
    }
}
