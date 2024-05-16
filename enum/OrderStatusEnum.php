<?php

namespace app\enum;

enum OrderStatusEnum: string
{
    case Draft = 'draft';
    case Approved = 'approved';
    case Collecting = 'collecting';
    case Delivering = 'delivering';
    case Delivered = 'delivered';
    case Rejected = 'rejected';
    case Refunded = 'refunded';

    public function getName(): string
    {
        return match ($this) {
            self::Draft => 'черновик',
            self::Approved => 'подтвержден',
            self::Collecting => 'на сборке',
            self::Delivering => 'в пути',
            self::Delivered => 'доставлен',
            self::Rejected => 'отказ',
            self::Refunded => 'возврат',
        };
    }

    public function getTextColorClass(): BootstrapColorClassEnum
    {
        return match ($this) {
            self::Draft => BootstrapColorClassEnum::LightEmphasis,
            self::Approved => BootstrapColorClassEnum::Success,
            self::Collecting => BootstrapColorClassEnum::PrimaryEmphasis,
            self::Delivering => BootstrapColorClassEnum::Primary,
            self::Delivered => BootstrapColorClassEnum::SuccessEmphasis,
            self::Rejected => BootstrapColorClassEnum::Danger,
            self::Refunded => BootstrapColorClassEnum::Warning,
        };
    }

    public function getAvailableStatuses(): array
    {
        return match ($this) {
            self::Draft => [self::Draft, self::Approved, self::Rejected],
            self::Approved => [self::Approved, self::Collecting],
            self::Collecting => [self::Collecting, self::Delivering],
            self::Delivering => [self::Delivering, self::Delivered, self::Refunded],
            self::Delivered => [],
            self::Rejected => [],
            self::Refunded => [],
            default => [$this],
        };
    }
}

