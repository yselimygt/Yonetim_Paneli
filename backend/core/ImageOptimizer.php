<?php

class ImageOptimizer {
    /**
     * Optimizes an image and saves it to the destination path.
     * Optionally resizes if width exceeds maxWidth.
     */
    public static function optimizeAndSave($sourcePath, $destinationPath, $maxWidth = 1920, $quality = 80) {
        if (!file_exists($sourcePath)) return false;
        
        $info = getimagesize($sourcePath);
        if (!$info) return false;

        $mime = $info['mime'];
        $image = null;

        switch ($mime) {
            case 'image/jpeg':
                $image = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($sourcePath);
                if ($image !== false) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($sourcePath);
                break;
            case 'image/gif':
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
}
