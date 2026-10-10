<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class ShortUrl extends Model
{
    use BelongsToTenant;

    protected $fillable = ['short_url', 'original_url', 'tenant_id', 'created_by', 'clicks'];

    public function tenant() {
        return $this->belongsTo(Tenant::class);
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
