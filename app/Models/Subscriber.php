<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Models;

use AltThree\Validator\ValidatingTrait;
use CachetHQ\Cachet\Presenters\SubscriberPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * This is the subscriber model.
 *
 * @author Joseph Cohen <joe@alt-three.com>
 * @author James Brooks <james@alt-three.com>
 * @author Graham Campbell <graham@alt-three.com>
 * @property int $id
 * @property string $email
 * @property string $verify_code
 * @property string $phone_number
 * @property string $slack_webhook_url
 * @property \Carbon\Carbon $verified_at
 * @property bool $global
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read bool $is_verified
 * @property-read \Illuminate\Database\Eloquent\Collection|\CachetHQ\Cachet\Models\Meta[] $meta
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\CachetHQ\Cachet\Models\Subscription[] $subscriptions
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscriber forComponent($component_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscriber isGlobal()
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscriber isVerified()
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscriber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscriber whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscriber whereGlobal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscriber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscriber wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscriber whereSlackWebhookUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscriber whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscriber whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscriber whereVerifyCode($value)
 * @mixin \Eloquent
 */
class Subscriber extends Model implements HasPresenter
{
    use Notifiable, ValidatingTrait;

    /**
     * The attributes that should be casted to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'email'             => 'string',
        'phone_number'      => 'string',
        'slack_webhook_url' => 'string',
        'verify_code'       => 'string',
        'verified_at'       => 'date',
        'global'            => 'bool',
    ];

    /**
     * The fillable properties.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'phone_number',
        'slack_webhook_url',
        'verified_at',
        'global',
    ];

    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'email'             => 'nullable|email',
        'phone_number'      => 'nullable|string',
        'slack_webhook_url' => 'nullable|url',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var string[]
     */
    protected $with = ['subscriptions'];

    /**
     * Overrides the models boot method.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($user) {
            if (!$user->verify_code) {
                $user->verify_code = self::generateVerifyCode();
            }
        });
    }

    /**
     * Get the meta relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function meta()
    {
        return $this->morphMany(Meta::class, 'meta');
    }

    /**
     * Get the subscriptions relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Scope verified subscribers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsVerified(Builder $query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope global subscribers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsGlobal(Builder $query)
    {
        return $query->where('global', '=', true);
    }

    /**
     * Finds all verified subscriptions for a component.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $component_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForComponent(Builder $query, $component_id)
    {
        return $query->select('subscribers.*')
            ->join('subscriptions', 'subscribers.id', '=', 'subscriptions.subscriber_id')
            ->where('subscriptions.component_id', '=', $component_id);
    }

    /**
     * Determines if the subscriber is verified.
     *
     * @return bool
     */
    public function getIsVerifiedAttribute()
    {
        return $this->verified_at !== null;
    }

    /**
     * Returns an new verify code.
     *
     * @return string
     */
    public static function generateVerifyCode()
    {
        return str_random(42);
    }

    /**
     * Route notifications for the Nexmo channel.
     *
     * @return string
     */
    public function routeNotificationForNexmo()
    {
        return $this->phone_number;
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        return $this->slack_webhook_url;
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return SubscriberPresenter::class;
    }
}
