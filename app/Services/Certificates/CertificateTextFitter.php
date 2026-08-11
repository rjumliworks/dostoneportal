<?php

namespace App\Services\Certificates;

use RuntimeException;

/**
 * Scales certificate text to fit its slot on the masked template, using GD to
 * measure real glyph widths from the same TTF files dompdf embeds.
 *
 * Templates are 2000x1414px and the PDF page is set to the same box in points
 * at 96 DPI, so offsets and widths below are in template pixels, one to one.
 */
class CertificateTextFitter
{
    /** GD takes a point size and renders at 96 DPI; dompdf takes CSS pixels. */
    private const POINTS_PER_PIXEL = 0.75;

    /** Width of the recipient name slot, ending where the rule below it ends. */
    private const NAME_WIDTH = 1220;

    /** Width of the body paragraph slot. */
    private const BODY_WIDTH = 1337;

    /** The body paragraph has room for three lines before the closing line. */
    private const BODY_LINES = 3;

    /**
     * Share of a line that wrapping can actually fill. Line breaks fall on word
     * boundaries, so the last word of each line usually leaves a gap.
     */
    private const LINE_PACKING = 0.95;

    /** Largest first; the artwork's own sizes are the first entry of each. */
    private const NAME_SIZES = [119, 104, 90, 76, 64];

    private const BODY_SIZES = [33, 30, 27, 24, 21];

    /**
     * The largest size that keeps the name on one line above the rule.
     */
    public function recipientFontSize(string $name): int
    {
        foreach (self::NAME_SIZES as $size) {
            if ($this->measure($name, 'Poppins-BoldItalic.ttf', $size) <= self::NAME_WIDTH) {
                return $size;
            }
        }

        return self::NAME_SIZES[array_key_last(self::NAME_SIZES)];
    }

    /**
     * The largest size that keeps the body paragraph inside its three lines.
     *
     * @param  list<array{text: string, style: string}>  $segments
     */
    public function bodyFontSize(array $segments): int
    {
        $capacity = self::BODY_WIDTH * self::LINE_PACKING * self::BODY_LINES;

        foreach (self::BODY_SIZES as $size) {
            $width = 0.0;

            foreach ($segments as $segment) {
                $font = $segment['style'] === 'plain' ? 'Roboto-Regular.ttf' : 'Roboto-Bold.ttf';
                $width += $this->measure($segment['text'], $font, $size);
            }

            if ($width <= $capacity) {
                return $size;
            }
        }

        return self::BODY_SIZES[array_key_last(self::BODY_SIZES)];
    }

    /**
     * Width of a string in template pixels, at a CSS pixel font size.
     */
    private function measure(string $text, string $fontFile, int $fontSizePx): float
    {
        $font = public_path("fonts/{$fontFile}");

        if (! is_file($font)) {
            throw new RuntimeException("Missing certificate font [{$font}].");
        }

        $box = imagettfbbox($fontSizePx * self::POINTS_PER_PIXEL, 0, $font, $text);

        if ($box === false) {
            throw new RuntimeException("Could not measure text with [{$fontFile}].");
        }

        return abs($box[2] - $box[0]);
    }
}
