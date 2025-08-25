<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'gender',
        'employee_id',
        'position',
        'phone',
        'address',
        'hire_date',
        'salary',
        'status',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Transaction (jika karyawan sebagai kasir)
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'user_id');
    }

    // Scope untuk karyawan aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope untuk karyawan berdasarkan posisi
    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'badge-success',
            'inactive' => 'badge-warning',
            'terminated' => 'badge-danger',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    // Accessor untuk format gaji
    public function getFormattedSalaryAttribute()
    {
        return $this->salary ? 'Rp ' . number_format($this->salary, 0, ',', '.') : '-';
    }

    // Accessor untuk format tanggal bergabung
    public function getFormattedHireDateAttribute()
    {
        return $this->hire_date ? $this->hire_date->format('d/m/Y') : '-';
    }

    public function getDisplayNameAttribute(): string
    {
        if (!empty($this->full_name)) {
            return $this->full_name;
        }
        return optional($this->user)->name ?? '-';
    }
}
