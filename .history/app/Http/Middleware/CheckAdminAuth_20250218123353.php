namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdminAuth
{
    public function handle($request, Closure $next)
    {
        // Ellenőrizd, hogy a felhasználó be van-e jelentkezve az 'admin' guarddal
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // Ha nincs bejelentkezve, akkor átirányítás a bejelentkezési oldalra
        return redirect()->route('admin.login');
    }
}