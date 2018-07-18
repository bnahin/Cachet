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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * CachetHQ\Cachet\Models\Subscription
 *
 * @property int $id
 * @property int $subscriber_id
 * @property int $component_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \CachetHQ\Cachet\Models\Component $component
 * @property-read \CachetHQ\Cachet\Models\Subscriber $subscriber
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscription forComponent($component_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscription forSubscriber($subscriber_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscription isVerifiedForComponent($component_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscription whereComponentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscription whereSubscriberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Subscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Subscription extends Model
{
    use ValidatingTrait;

    /**
     * The attributes that should be casted to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'subscriber_id' => 'int',
        'component_id'  => 'int',
    ];

    /**
     * The fillable properties.
     *
     * @var string[]
     */
    protected $fillable = [
        'subscriber_id',
        'component_id',
    ];

    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'subscriber_id' => 'required|int',
        'component_id'  => 'nullable|int',
    ];

    /**
     * Get the subscriber relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * Get the component relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    /**
     * Finds all subscriptions for a given subscriber.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $subscriber_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForSubscriber(Builder $query, $subscriber_id)
    {
        return $query->where('subscriber_id', '=', $subscriber_id);
    }

    /**
     * Finds all subscriptions for a component.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $component_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForComponent(Builder $query, $component_id)
    {
        return $query->where('component_id', '=', $component_id);
    }

    /**
     * Finds all verified subscriptions for a component.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $component_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsVerifiedForComponent(Builder $query, $component_id)
    {
        return $query->select('subscriptions.*')
            ->join('subscribers', 'subscriptions.subscriber_id', '=', 'subscribers.id')
            ->where(function ($query) {
                $query->where('subscriptions.component_id', '=', $component_id)
                    ->orWhere('subscribers.global');
            })
            ->whereNotNull('subscribers.verified_at');
    }
}
