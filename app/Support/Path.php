<?php

declare(strict_types=1);

namespace App\Support;

class Path
{
    /**
     * Get all files from a folder.
     *
     * @param string $path Path to folder
     * @return array
     */
    public static function getMultipleFiles(string $path): array
    {
        $firstPath  = glob($path . '/*.php');
        $secondPath = glob($path . '/*/*.php');
        $thirdPath  = glob($path . '/*/*/*.php');

        return array_merge($firstPath, $secondPath, $thirdPath);
    }
}
