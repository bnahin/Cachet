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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * This is the invite class.
 *
 * @author Joseph Cohen <joe@alt-three.com>
 * @author James Brooks <james@alt-three.com>
 * @property int $id
 * @property string $code
 * @property string $email
 * @property string|null $claimed_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read bool $is_claimed
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Invite whereClaimedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Invite whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Invite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Invite whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Invite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Invite whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Invite extends Model
{
    use Notifiable;

    /**
     * The attributes that should be casted to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'email' => 'string',
    ];

    /**
     * The fillable properties.
     *
     * @var string[]
     */
    protected $fillable = ['email'];

    /**
     * Overrides the models boot method.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($invite) {
            if (!$invite->code) {
                $invite->code = str_random(20);
            }
        });
    }

    /**
     * Determines if the invite was claimed.
     *
     * @return bool
     */
    public function getIsClaimedAttribute()
    {
        return $this->claimed_at !== null;
    }
}
