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
use Illuminate\Database\Eloquent\Model;

/**
 * This is the taggable model class.
 *
 * @author James Brooks <james@alt-three.com>
 * @property int $id
 * @property int $tag_id
 * @property string $taggable_type
 * @property int $taggable_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \CachetHQ\Cachet\Models\Tag $tag
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $taggable
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Taggable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Taggable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Taggable whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Taggable whereTaggableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Taggable whereTaggableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Taggable whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Taggable extends Model
{
    use ValidatingTrait;

    /**
     * The attributes that should be casted to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'id'            => 'int',
        'tag_id'        => 'int',
        'taggable_id'   => 'int',
        'taggable_type' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag_id',
        'taggable_id',
        'taggable_type',
    ];

    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'tag_id'        => 'required|int',
        'taggable_id'   => 'required|int',
        'taggable_type' => 'required|string',
    ];

    /**
     * Get the tag relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Get the taggable relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function taggable()
    {
        return $this->morphTo();
    }
}
