<?php

namespace Gcr\Policies;

use Gcr\User;
use Gcr\Process;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProcessPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the process.
     *
     * @param User $user
     * @param Process $process
     * @return mixed
     */
    public function view(User $user, Process $process)
    {
        return $user->id === $process->user_id;
    }

    /**
     * Determine whether the user can create processes.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the process.
     *
     * @param User $user
     * @param Process $process
     * @return mixed
     */
    public function update(User $user, Process $process)
    {
        if (!$process->isEditing()) {
            return false;
        }
        return $user->id === $process->user_id;
    }

    /**
     * Determine whether the user can delete the process.
     *
     * @param User $user
     * @param Process $process
     * @return mixed
     */
    public function delete(User $user, Process $process)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the process.
     *
     * @param User $user
     * @param Process $process
     * @return mixed
     */
    public function restore(User $user, Process $process)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the process.
     *
     * @param User $user
     * @param Process $process
     * @return mixed
     */
    public function forceDelete(User $user, Process $process)
    {
        return false;
    }
}
