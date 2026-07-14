<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Cat;
use Illuminate\Auth\Access\HandlesAuthorization;

class CatPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Cat');
    }

    public function view(AuthUser $authUser, Cat $cat): bool
    {
        return $authUser->can('View:Cat');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Cat');
    }

    public function update(AuthUser $authUser, Cat $cat): bool
    {
        return $authUser->can('Update:Cat');
    }

    public function delete(AuthUser $authUser, Cat $cat): bool
    {
        return $authUser->can('Delete:Cat');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Cat');
    }

    public function restore(AuthUser $authUser, Cat $cat): bool
    {
        return $authUser->can('Restore:Cat');
    }

    public function forceDelete(AuthUser $authUser, Cat $cat): bool
    {
        return $authUser->can('ForceDelete:Cat');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Cat');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Cat');
    }

    public function replicate(AuthUser $authUser, Cat $cat): bool
    {
        return $authUser->can('Replicate:Cat');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Cat');
    }

}