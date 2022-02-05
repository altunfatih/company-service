<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryBalance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function receiver() {
        return $this->belongsTo(User::class, 'received_service_id')->select('id', 'name', 'email', 'type');
    }

    public function service() {
        return $this->belongsTo(Service::class)->select('id', 'service_name', 'price');
    }
}
