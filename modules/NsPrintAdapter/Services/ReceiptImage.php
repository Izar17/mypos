<?php
namespace Modules\NsPrintAdapter\Services;

use Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ReceiptImage
{
    private $image;
    private $color;
    private $bold = false;
    private $align = 'left';
    private $lineHeight = 14;
    private $currentLine = 0;
    private $fontSize = 12;
    private $fontFamily = 'arial.ttf';
    private $fontFamilies   =   [];
    private $width;
    private $height;

    public function __construct( public $mm = 80, public $ppi = 180 )
    {
        $famillies   =   [ 'roboto', 'arabic', 'chinese' ];
        
        $this->fontFamilies     =   collect( $famillies )->mapWithKeys( function( $familly ) {
            return [ 
                $familly => [
                    'regular'   =>  dirname( __FILE__ ) . '/../Public/fonts' . DIRECTORY_SEPARATOR . $familly . DIRECTORY_SEPARATOR . 'regular.ttf',
                    'bold'      =>  dirname( __FILE__ ) . '/../Public/fonts' . DIRECTORY_SEPARATOR . $familly . DIRECTORY_SEPARATOR . 'bold.ttf',
                    'italic'    =>  dirname( __FILE__ ) . '/../Public/fonts' . DIRECTORY_SEPARATOR . $familly . DIRECTORY_SEPARATOR . 'italic.ttf',
                    'bold-italic'   =>  dirname( __FILE__ ) . '/../Public/fonts' . DIRECTORY_SEPARATOR . $familly . DIRECTORY_SEPARATOR . 'bold-italic.ttf',
                ]
            ];
        });

        $this->setFontFamily( ns()->option->get( 'ns_pa_font_familly', 'roboto' ) );

        $this->width = $this->mm * $this->ppi / 25.4;
        $this->height = 5000;
        $this->image = imagecreatetruecolor($this->width, $this->height);
        $this->color = imagecolorallocate($this->image, 0, 0, 0); // Black text
        imagefill($this->image, 0, 0, imagecolorallocate($this->image, 255, 255, 255)); // White background
    }

    private function isRtl($text)
    {
        $rtlChar = '/[\p{Arabic}\p{Hebrew}]/u';
        return preg_match($rtlChar, $text);
    }

    private function reverseText($text)
    {
        return mb_convert_encoding(strrev(mb_convert_encoding($text, 'UTF-16BE', 'UTF-8')), 'UTF-8', 'UTF-16LE');
    }

    public function bold($bold = true)
    {
        $this->bold = $bold;
        return $this;
    }
    
    public function writeLine($left, $right)
    {
        // Reverse the text if it's RTL
        if ($this->isRtl($left)) {
            $left = $this->reverseText($left);
        }
        if ($this->isRtl($right)) {
            $right = $this->reverseText($right);
        }
    
        // Write the first text at the left edge of the image
        $y = $this->currentLine * $this->lineHeight + $this->fontSize;
        imagettftext($this->image, $this->fontSize, 0, 0, $y, $this->color, $this->fontFamily, $left);

        // Calculate the width of the second text
        $text_box2 = imagettfbbox($this->fontSize, 0, $this->fontFamily, $right);
        $text_width2 = $text_box2[2] - $text_box2[0];

        // Calculate the position of the second text
        $x2 = imagesx($this->image) - $text_width2;

        // Write the second text at the calculated position
        imagettftext($this->image, $this->fontSize, 0, $x2, $y, $this->color, $this->fontFamily, $right);

        $this->currentLine++;

        return $this;
    }

    public function alignRight()
    {
        $this->align = 'right';
        return $this;
    }

    public function alignLeft()
    {
        $this->align = 'left';
        return $this;
    }

    public function alignCenter()
    {
        $this->align = 'center';
        return $this;
    }

    public function newLine($count = 1)
    {
        $this->currentLine += $count;
        return $this;
    }

    public function addImage($imageUrl)
    {
        $contextOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];
        
        $context = stream_context_create($contextOptions);

        // Load the image from the URL
        $srcImage = imagecreatefromstring(file_get_contents($imageUrl, false, $context ));

        // Get the dimensions of the source image
        $srcWidth = imagesx($srcImage);
        $srcHeight = imagesy($srcImage);

        // Calculate the new dimensions
        $newWidth = $srcWidth;
        $newHeight = $srcHeight;

        // If the image is wider than the receipt, resize it
        if ($newWidth > $this->width) {
            $newWidth = $this->width;
            $newHeight = ($srcHeight / $srcWidth) * $newWidth;
        }

        // Create a new true color image with the new dimensions
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        $white = imagecolorallocate($newImage, 255, 255, 255);
        imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $white);

        // Resample the source image into the new image
        imagecopyresampled($newImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);

        // Calculate the x-coordinate for center alignment
        $x = ($this->width - $newWidth) / 2;

        // Copy the new image onto the receipt image
        imagecopy($this->image, $newImage, $x, $this->currentLine * $this->lineHeight, 0, 0, $newWidth, $newHeight);

        // Update the current line
        $totalLines = ceil( $newHeight / $this->lineHeight );

        $this->currentLine += $totalLines;

        // Clean up
        imagedestroy($srcImage);
        imagedestroy($newImage);

        return $this;
    }

    public function write($text, $sameLine = false)
    {
        if ( strpos( $text, "\n" ) !== false ) {
            // Split the text into lines
            $lines = explode("\n", $text);

            // Loop over the lines and call $this->write on each one
            foreach ($lines as $line) {
                $this->write($line);
                $this->newLine();
            }

            return $this;
        }

        // Calculate the width of the text
        $text_box = imagettfbbox($this->fontSize, 0, $this->fontFamily, $text);
        $text_width = $text_box[2] - $text_box[0];

        // If the text is too wide, split it into multiple lines
        if ($text_width > imagesx($this->image)) {
            $words = explode(' ', $text);
            $lines = array($words[0]);
            $currentLine = 0;

            for ($i = 1; $i < count($words); $i++) {
                $lineSize = imagettfbbox($this->fontSize, 0, $this->fontFamily, $lines[$currentLine] . ' ' . $words[$i]);
                if ($lineSize[2] - $lineSize[0] < imagesx($this->image)) {
                    $lines[$currentLine] .= ' ' . $words[$i];
                } else {
                    $lines[++$currentLine] = $words[$i];
                }
            }

            foreach ($lines as $line) {
                $this->write($line);
            }
        } else {
            // If the text is in a RTL language, reverse it
            if ( $this->isRtl($text) ) {
                $text = strrev($text);
            }
            
            // Calculate the x-coordinate of the text based on the alignment
            switch ($this->align) {
                case 'center':
                    $x = (imagesx($this->image) - $text_width) / 2;
                    break;
                case 'right':
                    $x = imagesx($this->image) - $text_width;
                    break;
                default: // 'left'
                    $x = 0;
                    break;
            }

            // Write the text at the calculated position
            $y = ( $this->currentLine * $this->lineHeight ) + $this->fontSize;
            imagettftext($this->image, $this->fontSize, 0, $x, $y, $this->color, $this->fontFamily, $text);

            if (!$sameLine) {
                $this->currentLine  += 1;
            }
        }

        return $this;
    }

    public function getBase64()
    {
        ob_start(); // Start output buffering
        imagepng( $this->cropCurrentImage() ); // Output the image
        $imageData = ob_get_clean(); // Get the image data from the buffer

        // Convert the image data to base64 and return it
        return base64_encode($imageData);
    }

    public function cropCurrentImage()
    {
        // Save the image temporarily
        $tempPath = storage_path( 'temporary-files' ) . DIRECTORY_SEPARATOR . 'temp.png';
        imagepng($this->image, $tempPath);

        // Reload the image
        $this->image = imagecreatefrompng($tempPath);

        // Find the last white line
        $white = imagecolorallocate($this->image, 255, 255, 255);
        $lastWhiteY = 0;
        for ($y = imagesy($this->image) - 1; $y >= 0; $y--) {
            $isWhiteLine = true;
            for ($x = 0; $x < imagesx($this->image); $x++) {
                $rgb = imagecolorat($this->image, $x, $y);
                if ($rgb != $white) {
                    $isWhiteLine = false;
                    break;
                }
            }
            if (!$isWhiteLine) {
                break;
            }
            $lastWhiteY = $y;
        }

        unlink($tempPath); // Delete the temporary image

        // Check if $lastWhiteY is a valid height for the image
        if ($lastWhiteY < 1 || $lastWhiteY > imagesy($this->image)) {
            throw new Exception('Invalid crop height');
        }

        // Crop the image
        return imagecrop($this->image, ['x' => 0, 'y' => 0, 'width' => imagesx($this->image), 'height' => $lastWhiteY]);
    }

    public function save($path) {
        $cropped = $this->cropCurrentImage();

        // Save the cropped image
        imagepng($cropped, $path);

        // Clean up
        imagedestroy($this->image);
        imagedestroy($cropped);
    }

    public function setFontSize($size)
    {
        $this->fontSize = $size;
        return $this;
    }

    public function setFontFamily($fontFamily, $weight = 'regular' )
    {
        $this->fontFamily = $this->fontFamilies[$fontFamily][ $weight ];
        return $this;
    }

    private function drawSingleLine($x1, $y1, $x2, $y2)
    {
        imageline($this->image, $x1, $y1, $x2, $y2, $this->color);
        return $this;
    }

    private function drawDoubleLine($x1, $y1, $x2, $y2, $distance = 3)
    {
        imageline($this->image, $x1, $y1, $x2, $y2, $this->color);
        imageline($this->image, $x1, $y1 + $distance, $x2, $y2 + $distance, $this->color);
        return $this;
    }

    private function drawDashedLine($x1, $y1, $x2, $y2)
    {
        $style = array($this->color, $this->color, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT);
        imagesetstyle($this->image, $style);
        imageline($this->image, $x1, $y1, $x2, $y2, IMG_COLOR_STYLED);
        return $this;
    }

    private function drawDoubleDashedLine($x1, $y1, $x2, $y2, $distance = 3)
    {
        $style = array($this->color, $this->color, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT);
        imagesetstyle($this->image, $style);
        imageline($this->image, $x1, $y1, $x2, $y2, IMG_COLOR_STYLED);
        imageline($this->image, $x1, $y1 + $distance, $x2, $y2 + $distance, IMG_COLOR_STYLED);
        return $this;
    }

    public function drawSeparator($style = 'single', $distance = 3)
    {
        $y = $this->currentLine * $this->lineHeight + $this->fontSize;
        $x1 = 0;
        $x2 = imagesx($this->image);

        switch ($style) {
            case 'double':
                $this->drawDoubleLine($x1, $y, $x2, $y, $distance);
                break;
            case 'dashed':
                $this->drawDashedLine($x1, $y, $x2, $y);
                break;
            case 'double-dashed':
                $this->drawDoubleDashedLine($x1, $y, $x2, $y, $distance);
                break;
            case 'single':
            default:
                $this->drawSingleLine($x1, $y, $x2, $y);
                break;
        }

        $this->currentLine++;
        return $this;
    }
    
    public function setLineHeight($lineHeight)
    {
        $this->lineHeight = $lineHeight;
        return $this;
    }
}