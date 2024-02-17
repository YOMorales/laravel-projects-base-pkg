<?php

namespace App\Base\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRun extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_SUCCESS = 1;
    public const STATUS_PARTIAL_SUCCESS = 2;
    public const STATUS_FAILURE = 3;

    public const TAG_ORDERS = 'purchase-order';

    protected $table = 'job_runs';

    protected $fillable = [
        'tag',
        'job_class',
        'last_run_date',
        'status'
    ];
}
