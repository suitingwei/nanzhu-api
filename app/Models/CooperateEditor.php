<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CooperateEditor extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class, 'editor_id', 'FID');
    }
}
