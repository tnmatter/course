<?php

namespace app\enum;

enum BootstrapColorClassEnum: string
{
    case Primary = 'primary';
    case PrimaryEmphasis = 'primary-emphasis';
    case Secondary = 'secondary';
    case SecondaryEmphasis = 'secondary-emphasis';
    case Success = 'success';
    case SuccessEmphasis = 'success-emphasis';
    case Danger = 'danger';
    case DangerEmphasis = 'danger-emphasis';
    case Warning = 'warning';
    case WarningEmphasis = 'warning-emphasis';
    case Info = 'info';
    case InfoEmphasis = 'info-emphasis';
    case Light = 'light';
    case LightEmphasis = 'light-emphasis';
    case Dark = 'dark';
    case DarkEmphasis = 'dark-emphasis';
    case Body = 'body';
    case BodyEmphasis = 'body-emphasis';
    case BodySecondary = 'body-secondary';
    case BodyTertiary = 'body-tertiary';
    case Black = 'black';
    case White = 'white';
    case Black50 = 'black-50';
    case White50 = 'white-50';

    public function getTextClass(): string
    {
        return "text-$this->value";
    }
}
