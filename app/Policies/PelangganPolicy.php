<?php

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class PelangganPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Pelanggan');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->can('View:Pelanggan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Pelanggan');
    }

    public function update(AuthUser $authUser): bool
    {
        return $authUser->can('Update:Pelanggan');
    }

    public function delete(AuthUser $authUser): bool
    {
        return $authUser->can('Delete:Pelanggan');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Pelanggan');
    }

    public function restore(AuthUser $authUser): bool
    {
        return $authUser->can('Restore:Pelanggan');
    }

    public function forceDelete(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDelete:Pelanggan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Pelanggan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Pelanggan');
    }

    public function replicate(AuthUser $authUser): bool
    {
        return $authUser->can('Replicate:Pelanggan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Pelanggan');
    }

}