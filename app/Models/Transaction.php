<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'amount',
        'due_date',
        'repeat_type',
        'frequency',
        'installments_total',
        'installment_number',
        'parent_transaction_id',
        'is_paid',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    protected $appends = [
        'is_series',
        'status_key',
        'status_text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Transaction::class, 'parent_transaction_id');
    }

    public function children()
    {
        return $this->hasMany(Transaction::class, 'parent_transaction_id');
    }

    public function getSeriesRootIdAttribute(): ?int
    {
        if (empty($this->parent_transaction_id)) {
            return null;
        }

        return (int) $this->parent_transaction_id;
    }

    public function scopeFromSameSeries(Builder $query, self $transaction): Builder
    {
        $rootId = $transaction->series_root_id;

        if (empty($rootId)) {
            return $query->where('id', $transaction->id);
        }

        return $query
            ->where('user_id', $transaction->user_id)
            ->where(function ($q) use ($rootId) {
                $q->where('id', $rootId)
                  ->orWhere('parent_transaction_id', $rootId);
            });
    }

    public function getIsSeriesAttribute(): bool
    {
        return $this->repeat_type !== 'none' && !empty($this->parent_transaction_id);
    }

    public function getStatusKeyAttribute(): string
    {
        $today = Carbon::today();

        if ($this->type === 'expense') {
            if ($this->is_paid) {
                return 'expense_paid';
            }

            if ($this->due_date->lt($today)) {
                return 'expense_overdue';
            }

            return 'expense_pending';
        }

        if ($this->type === 'income') {
            if ($this->is_paid) {
                return 'income_received';
            }

            if ($this->due_date->lt($today)) {
                return 'income_overdue';
            }

            return 'income_pending';
        }

        return 'unknown';
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status_key) {
            'expense_paid' => 'Esta despesa já foi paga.',
            'expense_overdue' => 'Esta despesa está vencida.',
            'expense_pending' => 'Esta despesa está pendente e no prazo.',

            'income_received' => 'Esta receita já foi recebida.',
            'income_overdue' => 'Esta receita está vencida.',
            'income_pending' => 'Esta receita está pendente e no prazo.',

            default => 'Status não identificado.',
        };
    }

    public function getStatusShortTextAttribute(): string
    {
        return match ($this->status_key) {
            'expense_paid' => 'Paga',
            'expense_overdue' => 'Vencida',
            'expense_pending' => 'Pendente',

            'income_received' => 'Recebida',
            'income_overdue' => 'Vencida',
            'income_pending' => 'Pendente',

            default => 'Indefinido',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status_key) {
            'expense_paid', 'income_received' => 'paid',
            'expense_overdue', 'income_overdue' => 'overdue',
            'expense_pending', 'income_pending' => 'pending',
            default => 'pending',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status_key) {
            'expense_paid', 'income_received' => 'success',
            'expense_overdue', 'income_overdue' => 'danger',
            'expense_pending', 'income_pending' => 'warning',
            default => 'warning',
        };
    }
}