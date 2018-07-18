<?php

namespace CachetHQ\Cachet\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * CachetHQ\Cachet\Models\EcrchsService
 *
 * @property int $id
 * @property string $service_name
 * @property int $component_id
 * @property string $ip_address
 * @property string|null $uptime
 * @property string|null $downtime
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\EcrchsService whereComponentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\EcrchsService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\EcrchsService whereDowntime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\EcrchsService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\EcrchsService whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\EcrchsService whereServiceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\EcrchsService whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\CachetHQ\Cachet\Models\EcrchsService whereUptime($value)
 * @mixin \Eloquent
 */
class EcrchsService extends Model
{
    protected $guarded = [];

    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
