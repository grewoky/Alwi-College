<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleMiddleware
{
    // Pakai di route: ->middleware('role:admin') atau 'role:teacher|admin'
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Tanpa parameter role => lolos
        if (empty($roles)) {
            return $next($request);
        }

        $user = Auth::user();

        // Ambil daftar nama role user via DB (tanpa Spatie methods)
        $userRoleNames = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', get_class($user)) // biasanya "App\Models\User"
            ->where('model_has_roles.model_id', $user->id)
            ->pluck('roles.name')
            ->toArray();

        // Admin boleh akses semua
        if (in_array('admin', $userRoleNames, true)) {
            return $next($request);
        }

        // Lolos jika user punya salah satu role yang diminta
        foreach ($roles as $required) {
            // dukung format "teacher|admin"
            foreach (explode('|', $required) as $single) {
                if (in_array(trim($single), $userRoleNames, true)) {
                    return $next($request);
                }
            }
        }

        abort(403, 'Unauthorized.');
    }
}
