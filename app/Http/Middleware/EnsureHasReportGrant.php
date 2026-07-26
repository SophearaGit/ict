<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class EnsureHasReportGrant
{
    /**
     * Only staff with admin_approval_edit_staff = 1 may reach routes protected by this
     * middleware (the Student/Staff/Intern report review pages). Regular staff attempting
     * to hit these URLs directly get a 403 instead of the page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || (int) $user->admin_approval_edit_staff !== 1) {
            abort(403, 'You do not have access to review reports.');
        }
        return $next($request);
    }
}
