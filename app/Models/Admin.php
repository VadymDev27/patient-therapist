<?php

namespace App\Models;

use App\Interfaces\UserInterface;
use App\Notifications\AdminInvitation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Admin extends User
{
    public const PERMISSIONS = [
        'download-data' => 'Download data',
        'edit-settings' => 'Edit weekly settings',
        'create-test' => 'Make test users',
        'invite-admin' => 'Invite new admins',
        'edit-admin' => 'Edit admin permissions',
        'delete-admin' => 'Delete admins',
    ];

    protected $table = 'users';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'password',
        'admin_permissions',
        'is_admin'
    ];

    protected $hidden = [
        'is_test',
        'test_time_travel',
        'test_can_go_ahead'
    ];

    protected $visible = ['email','admin_permissions'];

    protected static function booted()
    {
        static::addGlobalScope('admins', function (Builder $query) {
            $query->where('is_admin', true);
        });
    }

    public function canDo($action): bool
    {
        return in_array($action, $this->admin_permissions);
    }

    private function isMaster(): bool
    {
        return $this->email === config('auth.admin.email');
    }

    public function canBeEdited(): bool
    {
        return Auth::user()->canDo('edit-admin')
            && ! $this->isMaster()
            && ! $this->is(Auth::user());
    }

    public function canBeDeleted(): bool
    {
        return Auth::user()->canDo('delete-admin')
            && ! $this->isMaster()
            && ! $this->is(Auth::user());
    }

    public function sendPasswordSetNotification($token)
    {
        $this->notify(new AdminInvitation($token));
    }

    public static function master(): Admin
    {
        return static::query()->where('email', config('auth.admin.email'))->sole();
    }
}
