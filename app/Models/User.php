<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'username',
        'company',
        'role',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRoleEnum::class,
        ];
    }

    /**
     * Get the parents of the user
     * $parents = $user->parents;
     *
     * Get the children of the user
     * $children = $user->children;
     *
     * Attach a parent to a user
     * $user->parents()->attach($parentUserId);
     *
     * Detach a parent from a user
     * $user->parents()->detach($parentUserId);
     *
     * Sync parents of a user (replaces existing parents)
     * $user->parents()->sync([$parentUserId1, $parentUserId2]);
     *
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_user', 'user_id', 'parent_id');
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_user', 'parent_id', 'user_id');
    }

    public function isSuperAdmin()
    {
        return $this->role == UserRoleEnum::SUPERADMIN;
    }

    public function isAdmin()
    {
        return $this->role == UserRoleEnum::ADMIN;
    }

    public function isUser()
    {
        return $this->role == UserRoleEnum::USER;
    }

    public function getActiveProfile()
    {
        return Cookie::get('profileId');
    }

    public function setActiveProfile($id)
    {
        return Cookie::queue('profileId', $id, 5);
    }

    public function getProfiles()
    {
        if ($this->isSuperAdmin()) {
            $users = User::get();
        } elseif ($this->isAdmin()) {
            $users = $this->children;
        } else {
            $users = $this->children;
        }
        $items = [];
        if (!$this->isSuperAdmin()) {
            $items[$this->id] = [
                'name' => $this->name . ' (Me)',
            ];
        }
        if (!empty($users) && count($users)) {
            foreach ($users as $user) {
                $user_name = $user->company ?? '';
                $user_name = $user->name . ' ' . ($user->lastname ?? '');
                $items[$user->id] = [
                    'name' => $user_name,
                ];
            }
        }
        return $items;
    }

    public function canImpersonate(): bool
    {
        return ($this->isAdmin() || $this->isSuperAdmin());
    }

    public function canBeImpersonated(): bool
    {
        return !$this->isSuperAdmin();
    }

    public function profilePhotoUrl(): Attribute
    {
        return Attribute::get(function () {

            $path = $this->profile_photo_path;

            if ($path != null && Storage::disk($this->profilePhotoDisk())->exists($path)) {
                return Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path);
            } elseif ($path != null && !empty($path)) {
                // Use Photo URL from Social sites link...
                return $path;
            } else {
                //empty path. Use defaultProfilePhotoUrl
                return $this->defaultProfilePhotoUrl();
            }
        });
    }
}
