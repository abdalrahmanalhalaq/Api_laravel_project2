<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\News;
use Illuminate\Auth\Access\Response;

class NewsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool //Admin $admin انشأهم من الديفولت جارد
    {
        //
        return $admin->hasPermissionTo('in-News');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $admin, News $news): bool // بدي افحص إذا كان الادمن الو صلاحية يصل للكاتيجوري ولا لا
    {
        //

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, News $news): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, News $news): bool
    {
        //\
        // return $admin->hasPermissionTo('delete-News');
        return true ;


    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin, News $news): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $admin, News $news): bool
    {
        //
    }
}
