<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;
    protected $table = 'verification_codes';

    protected $fillable = [
        'user_id',
        'code',
    ];

    /**
     * Obtener el usuario al que pertenece el código de verificación.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsUsed()
    {
        $this->is_used = true;
        $this->save();
    }
}
