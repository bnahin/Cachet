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
use CachetHQ\Cachet\Models\Traits\HasTags;
use CachetHQ\Cachet\Models\Traits\SearchableTrait;
use CachetHQ\Cachet\Models\Traits\SortableTrait;
use CachetHQ\Cachet\Presenters\ComponentPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * CachetHQ\Cachet\Models\Component
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $link
 * @property int $status
 * @property int $order
 * @property int $group_id
 * @property bool $enabled
 * @property \Illuminate\Database\Eloquent\Collection|\CachetHQ\Cachet\Models\Meta[] $meta
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \CachetHQ\Cachet\Models\ComponentGroup $group
 * @property-read \Illuminate\Database\Eloquent\Collection|\CachetHQ\Cachet\Models\Incident[] $incidents
 * @property-read \Illuminate\Database\Eloquent\Collection|\CachetHQ\Cachet\Models\Tag[] $tags
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component authenticated($authenticated)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component disabled()
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component enabled()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component grouped()
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component notStatus($status)
 * @method static \Illuminate\Database\Query\Builder|\CachetHQ\Cachet\Models\Component onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component search($search = array())
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component sort($column, $direction)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component ungrouped()
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component withAllTags($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\Component withAnyTags($tags)
 * @method static \Illuminate\Database\Query\Builder|\CachetHQ\Cachet\Models\Component withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\CachetHQ\Cachet\Models\Component withoutTrashed()
 * @mixin \Eloquent
 * @property-read \CachetHQ\Cachet\Models\EcrchsService $service
 */
class Component extends Model implements HasPresenter
{
    use HasTags, SearchableTrait, SoftDeletes, SortableTrait, ValidatingTrait;

    /**
     * List of attributes that have default values.
     *
     * @var mixed[]
     */
    protected $attributes = [
        'order'       => 0,
        'group_id'    => 0,
        'description' => '',
        'link'        => '',
        'enabled'     => true,
        'meta'        => null,
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'name'        => 'string',
        'description' => 'string',
        'status'      => 'int',
        'order'       => 'int',
        'link'        => 'string',
        'group_id'    => 'int',
        'enabled'     => 'bool',
        'meta'        => 'json',
        'deleted_at'  => 'date',
    ];

    /**
     * The fillable properties.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'link',
        'order',
        'group_id',
        'enabled',
        'meta',
    ];

    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'name'     => 'required|string',
        'status'   => 'required|int',
        'order'    => 'nullable|int',
        'group_id' => 'nullable|int',
        'link'     => 'nullable|url',
        'enabled'  => 'required|bool',
    ];

    /**
     * The searchable fields.
     *
     * @var string[]
     */
    protected $searchable = [
        'id',
        'name',
        'status',
        'order',
        'group_id',
        'enabled',
    ];

    /**
     * The sortable fields.
     *
     * @var string[]
     */
    protected $sortable = [
        'id',
        'name',
        'status',
        'order',
        'group_id',
        'enabled',
    ];

    /**
     * Get the group relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(ComponentGroup::class, 'group_id', 'id');
    }

    /**
     * Get the incidents relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incidents()
    {
        return $this->hasMany(Incident::class, 'component_id', 'id');
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
     * Finds all components by status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $status
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus(Builder $query, $status)
    {
        return $query->where('status', '=', $status);
    }

    /**
     * Finds all components which don't have the given status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $status
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotStatus(Builder $query, $status)
    {
        return $query->where('status', '<>', $status);
    }

    /**
     * Finds all components which are enabled.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled(Builder $query)
    {
        return $query->where('enabled', '=', true);
    }

    /**
     * Find all components which are within visible groups.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool                                  $authenticated
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthenticated(Builder $query, $authenticated)
    {
        return $query->when(!$authenticated, function (Builder $query) {
            return $query->whereDoesntHave('group', function (Builder $query) {
                $query->where('visible', ComponentGroup::VISIBLE_AUTHENTICATED);
            });
        });
    }

    /**
     * Finds all components which are disabled.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisabled(Builder $query)
    {
        return $query->where('enabled', '=', false);
    }

    /**
     * Finds all ungrouped components.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUngrouped(Builder $query)
    {
        return $query->enabled()
            ->where('group_id', '=', 0)
            ->orderBy('order')
            ->orderBy('created_at');
    }

    /**
     * Finds all grouped components.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGrouped(Builder $query)
    {
        return $query->enabled()
            ->where('group_id', '>', 0)
            ->groupBy('group_id');
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return ComponentPresenter::class;
    }

    public function service() {
        return $this->hasOne(EcrchsService::class);
    }
}
