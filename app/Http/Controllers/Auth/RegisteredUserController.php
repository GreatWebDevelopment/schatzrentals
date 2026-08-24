<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class RegisteredUserController extends Controller
{
    /**
     * Public registration is permanently disabled — admin accounts are created
     * manually via tinker/seeder. The GWD preview builder overlays a template
     * routes/auth.php that re-adds the /register routes, so this controller
     * must 404 rather than relying on the routes file alone.
     */
    public function create(): never
    {
        abort(404);
    }

    public function store(): never
    {
        abort(404);
    }
}
