<?php

class ImageOptimizer {
    public static function canResizeImages(): bool {
        return function_exists('getimagesize')
            && function_exists('imagecreatetruecolor')
            && function_exists('imagecopyresampled')
            && function_exists('imagedestroy')
            && function_exists('imagejpeg');
    }

    public static function canOptimizeImages(): bool {
        return self::canResizeImages()
            && function_exists('imagewebp');
    }

    /**
     * Optimizes an image and saves it to the destination path.
     * Optionally resizes if width exceeds maxWidth.
     */
    public static function optimizeAndSave($sourcePath, $destinationPath, $maxWidth = 1920, $quality = 80) {
        if (!file_exists($sourcePath)) return false;
        
        $info = getimagesize($sourcePath);
        if (!$info) return false;

        if (!self::canOptimizeImages()) {
            return copy($sourcePath, $destinationPath);
        }

        $mime = $info['mime'];
        $image = null;

        switch ($mime) {
            case 'image/jpeg':
                if (!function_exists('imagecreatefromjpeg')) return copy($sourcePath, $destinationPath);
                $image = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                if (!function_exists('imagecreatefrompng')) return copy($sourcePath, $destinationPath);
                $image = @imagecreatefrompng($sourcePath);
                if ($image !== false) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'image/webp':
                if (!function_exists('imagecreatefromwebp')) return copy($sourcePath, $destinationPath);
                $image = @imagecreatefromwebp($sourcePath);
                break;
            case 'image/gif':
                if (!function_exists('imagecreatefromgif')) return copy($sourcePath, $destinationPath);
                $image = @imagecreatefromgif($sourcePath);
                break;
            default:
                // Unsupported for optimization, just copy
                return copy($sourcePath, $destinationPath);
        }

        if (!$image) return copy($sourcePath, $destinationPath);

        $width = $info[0];
        $height = $info[1];

        // Calculate new dimensions
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) floor($height * ($maxWidth / $width));
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            if ($mime === 'image/png' || $mime === 'image/webp') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $newImage;
        }

        $destExt = strtolower(pathinfo($destinationPath, PATHINFO_EXTENSION));
        $success = false;

        if ($destExt === 'webp') {
            $success = imagewebp($image, $destinationPath, $quality);
        } else if ($destExt === 'png') {
            $success = imagepng($image, $destinationPath, 8); 
        } else {
            $success = imagejpeg($image, $destinationPath, $quality);
        }

        imagedestroy($image);
        return $success;
    }

    public static function createThumbnail($sourcePath, $destinationPath, $targetWidth = 360, $targetHeight = 260, $quality = 78) {
        if (!file_exists($sourcePath) || !self::canResizeImages()) return false;

        $info = getimagesize($sourcePath);
        if (!$info) return false;

        $image = self::createImageResource($sourcePath, $info['mime']);
        if (!$image) return false;

        $sourceWidth = $info[0];
        $sourceHeight = $info[1];
        $sourceRatio = $sourceWidth / $sourceHeight;
        $targetRatio = $targetWidth / $targetHeight;

        if ($sourceRatio > $targetRatio) {
            $cropHeight = $sourceHeight;
            $cropWidth = (int) floor($sourceHeight * $targetRatio);
            $srcX = (int) floor(($sourceWidth - $cropWidth) / 2);
            $srcY = 0;
        } else {
            $cropWidth = $sourceWidth;
            $cropHeight = (int) floor($sourceWidth / $targetRatio);
            $srcX = 0;
            $srcY = (int) floor(($sourceHeight - $cropHeight) / 2);
        }

        $thumb = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($thumb, $image, 0, 0, $srcX, $srcY, $targetWidth, $targetHeight, $cropWidth, $cropHeight);

        $destExt = strtolower(pathinfo($destinationPath, PATHINFO_EXTENSION));
        $success = false;
        if ($destExt === 'webp' && function_exists('imagewebp')) {
            $success = imagewebp($thumb, $destinationPath, $quality);
        } else {
            $success = imagejpeg($thumb, $destinationPath, $quality);
        }

        imagedestroy($image);
        imagedestroy($thumb);
        return $success;
    }

    private static function createImageResource($sourcePath, $mime) {
        switch ($mime) {
            case 'image/jpeg':
                return function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($sourcePath) : false;
            case 'image/png':
                return function_exists('imagecreatefrompng') ? @imagecreatefrompng($sourcePath) : false;
            case 'image/webp':
                return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false;
            case 'image/gif':
                return function_exists('imagecreatefromgif') ? @imagecreatefromgif($sourcePath) : false;
            default:
                return false;
        }
    }
}
