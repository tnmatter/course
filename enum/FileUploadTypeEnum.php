<?php

namespace app\enum;

use app\services\upload\checker\ImageRequirementsChecker;
use app\services\upload\models\FileUploadTypeConfig;

enum FileUploadTypeEnum: string
{
    case PRODUCT_AVATAR = 'product_avatar';

    public function getConfig(): FileUploadTypeConfig
    {
        return match ($this) {
            self::PRODUCT_AVATAR => new FileUploadTypeConfig(
                'product-avatar',
                new ImageRequirementsChecker(
                    ['jpeg', 'jpg', 'png'],
                    minWidth: 100,
                    minHeight: 100,
                    ratio: 1,
                    ratioString: '1x1',
                ),
            ),
        };
    }
}
