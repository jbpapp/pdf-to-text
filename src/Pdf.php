<?php

namespace JBPapp\PdfToText;

use Symfony\Component\Process\Process;
use JBPapp\PdfToText\Exceptions\PdfNotFound;
use JBPapp\PdfToText\Exceptions\CouldNotExtractText;

class Pdf
{
    protected $pdf;

    protected $binPath;

    protected $binPaths = [
        '/usr/bin/pdftotext',
        '/usr/local/bin/pdftotext',
    ];

    public function __construct($binPath = null)
    {
        if ($binPath) {
            $this->binPath = $binPath;
        } else {
            foreach ($this->binPaths as $binPath) {
                if (file_exists($binPath)) {
                    $this->binPath = $binPath;
                }
            }
        }
    }

    public function setPdf($pdf)
    {
        if (!file_exists($pdf)) {
            throw new PdfNotFound("could not find pdf {$pdf}");
        }

        $this->pdf = $pdf;

        return $this;
    }

    public function text()
    {
        $process = new Process("{$this->binPath} {$this->pdf} -");
        $process->run();

        if (!$process->isSuccessful()) {
            throw new CouldNotExtractText($process);
        }

        return trim($process->getOutput(), " \t\n\r\0\x0B\x0C");
    }

    public static function getText($pdf, $binPath = null)
    {
        return (new static($binPath))
            ->setPdf($pdf)
            ->text();
    }
}
