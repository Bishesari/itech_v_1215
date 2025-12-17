<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpLog extends Model
{
    protected $fillable=['ip', 'n_code', 'contact_value', 'otp', 'otp_next_try_time', 'otp_expires_at'];
}
