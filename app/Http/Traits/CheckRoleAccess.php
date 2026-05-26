<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth;

trait CheckRoleAccess
{
    /**
     * Check if user has specific role
     */
    protected function requireRole($role)
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.')->send();
            exit;
        }
    }

    /**
     * Check if user has any of the roles
     */
    protected function requireRoles(...$roles)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.')->send();
            exit;
        }
    }
}
