<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Merek;
use Illuminate\Auth\Access\HandlesAuthorization;

class MerekPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Merek');
    }

    public function view(AuthUser $authUser, Merek $merek): bool
    {
        return $authUser->can('View:Merek');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Merek');
    }

    public function update(AuthUser $authUser, Merek $merek): bool
    {
        return $authUser->can('Update:Merek');
    }

    public function delete(AuthUser $authUser, Merek $merek): bool
    {
        return $authUser->can('Delete:Merek');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Merek');
    }

    public function restore(AuthUser $authUser, Merek $merek): bool
    {
        return $authUser->can('Restore:Merek');
    }

    public function forceDelete(AuthUser $authUser, Merek $merek): bool
    {
        return $authUser->can('ForceDelete:Merek');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Merek');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Merek');
    }

    public function replicate(AuthUser $authUser, Merek $merek): bool
    {
        return $authUser->can('Replicate:Merek');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Merek');
    }

}