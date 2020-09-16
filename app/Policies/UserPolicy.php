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
}
