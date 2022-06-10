<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\Admin\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Admin  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Admin $admin)
    {
        return $admin->hasPermission('brows_permissions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $admin, Permission $permission)
    {
        return $admin->hasPermission('read_permissions');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Admin $admin)
    {
        return $admin->hasPermission('add_permissions');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $admin, Permission $permission)
    {
        return $admin->hasPermission('edit_permissions');
    }

    /**
     * Determine whether the Admin can delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $admin, Permission $permission)
    {
        return $admin->hasPermission('delete_permissions');
    }

    /**
     * Determine whether the Admin can restore the model.
     *
     * @param  \App\Models\Admin  $Admin
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Admin $admin, Permission $permission)
    {
        return $admin->hasPermission('restore_permissions');
    }

    /**
     * Determine whether the Admin can permanently delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Admin $admin, Permission $permission)
    {
        return $admin->hasPermission('forceDelete_permissions');
    }
}
