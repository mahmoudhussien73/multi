<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Admin\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
        return $admin->hasPermission('brows_roles');
    }

    /**
     * Determine whether the admin can view the model.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $admin, Role $role)
    {
        return $admin->hasPermission('read_roles');
    }

    /**
     * Determine whether the admin can create models.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Admin $admin)
    {
        return $admin->hasPermission('add_roles');
    }

    /**
     * Determine whether the admin can update the model.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $admin, Role $role)
    {
        return $admin->hasPermission('edit_roles');
    }

    /**
     * Determine whether the admin can delete the model.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $admin, Role $role)
    {
        return $admin->hasPermission('delete_roles');
    }

    /**
     * Determine whether the admin can restore the model.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Admin $admin, Role $role)
    {
        return $admin->hasPermission('restore_roles');
    }

    /**
     * Determine whether the admin can permanently delete the model.
     *
     * @param  \App\Models\Admin\Admin  $admin
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Admin $admin, Role $role)
    {
        return $admin->hasPermission('forceDelete_roles');
    }
}
