<?php

namespace App\Policies;

use App\User;
use App\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Can Access Product
     */
    public function accessCompanyPages(User $user, Product $product)
    {
        
        // return in_array($user->company_id, );
        return $user->company_id == $product->company_id;
    }

    public function accessTeamAdmin(User $user)
    {
        $allowedRoles = array(1, 4);
        return in_array($user->role, $allowedRoles);
    }

    public function accessTeamEditor(User $user)
    {
        return $user->role === 5;
    }

    public function accessSuperAdmin(User $user)
    {
        return $user->role === 1;
    }
}
