<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\Report\Database\Factories\ReportFactory;

/**
 * @property int         $id
 * @property string      $status
 * @property null|string $file_path
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @method static \Modules\Report\Database\Factories\ReportFactory     factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'status',
        'created_at',
        'file_path',
    ];

    protected static function newFactory(): ReportFactory
    {
        return ReportFactory::new();
    }
}
