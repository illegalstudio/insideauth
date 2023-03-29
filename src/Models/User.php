<?php

namespace Illegal\InsideAuth\Models;

use Illegal\InsideAuth\Events\UserDeleted;
use Illegal\InsideAuth\InsideAuth;
use Illegal\LaravelUtils\Contracts\HasPrefix;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasPrefix;

    /**
     * Override the db prefix for this model.
     */
    public function getPrefix(): string
    {
        return config('inside_auth.db.prefix');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        self::deleting(function ($user) {
            /**
             * Delete all tokens associated with the user.
             */
            $user->tokens()->delete();

            /**
             * Dispatch the user deleted event, so that packages can react to it.
             */
            UserDeleted::dispatch($user);
        });
        parent::boot();
    }

    /**
     * Send the password reset notification.
     * Overriding the default implementation to use the InsideAuth custom URL.
     *
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        /**
         * @todo Use the authenticator
         */
        $notification = new ResetPasswordNotification($token);

        $notification::$createUrlCallback = function ($notifiable, $token) {
            return url(route(InsideAuth::current()->route_password_reset, [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        };

        $this->notify($notification);
    }

    /**
     * Send the email verification notification.
     * Overriding the default implementation to use the InsideAuth custom URL.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        if (!InsideAuth::current()->isEmailVerificationEnabled()) {
            return;
        }

        $notification = new VerifyEmail;

        $notification::$createUrlCallback = function ($notifiable) {
            return URL::temporarySignedRoute(
                InsideAuth::current()->route_verification_verify,
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id'   => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        };

        $this->notify($notification);
    }
}
