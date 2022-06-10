<?php

namespace App\Policies;

use App\Models\Admin\Admin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view any models.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Admin $admin)
    {
        return $admin->hasPermission('brows_admins');
    }

    /**
     * Determine whether the admin can view the model.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $admin)
    {
        return $admin->hasPermission('read_admins');
    }

    /**
     * Determine whether the admin can create models.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Admin $admin)
    {
        return $admin->hasPermission('add_admins');
    }

    /**
     * Determine whether the admin can update the model.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $admin)
    {
        return $admin->hasPermission('edit_admins');
    }

    /**
     * Determine whether the admin can delete the model.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $admin)
    {
        return $admin->hasPermission('delete_admins');
    }

    /**
     * Determine whether the admin can restore the model.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Admin $admin)
    {
        return $admin->hasPermission('restore_admins');
    }

    /**
     * Determine whether the admin can permanently delete the model.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Admin $admin)
    {
        return $admin->hasPermission('forceDelete_admins');
    }
}
