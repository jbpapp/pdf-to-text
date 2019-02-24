<?php

namespace JBPapp\PdfToText\Test;

use JBPapp\PdfToText\Exceptions\CouldNotExtractText;
use JBPapp\PdfToText\Exceptions\PdfNotFound;
use JBPapp\PdfToText\Pdf;

class PdfToTextTest extends \PHPUnit\Framework\TestCase
{
    protected $dummyPdf = __DIR__.'/testfiles/dummy.pdf';

    protected $dummyPdfText = 'This is a dummy PDF';

    /** @test */
    public function it_can_extract_text_from_a_pdf()
    {
        $text = (new Pdf())
            ->setPdf($this->dummyPdf)
            ->text();

        $this->assertSame($this->dummyPdfText, $text);
    }

    /** @test */
    public function it_provides_a_static_method_to_extract_text()
    {
        $this->assertSame($this->dummyPdfText, Pdf::getText($this->dummyPdf));
    }

    /** @test */
    public function it_extracts_text_froom_a_pdf_with_spaces_in_the_name()
    {
        $this->dummyPdf = __DIR__.'/testfiles/pdf with spaces.pdf';
        $this->assertSame($this->dummyPdfText, Pdf::getText($this->dummyPdf));
    }

    /** @test */
    public function it_will_throw_an_exception_when_the_pdf_is_not_found()
    {
        $this->expectException(PdfNotFound::class);

        (new Pdf())
            ->setPdf('/no/pdf/here/dummy.pdf')
            ->text();
    }

    /** @test */
    public function it_will_throw_an_exception_when_the_binary_is_not_found()
    {
        $this->expectException(CouldNotExtractText::class);

        (new Pdf('/there/is/no/place/like/home/pdftotext'))
            ->setPdf($this->dummyPdf)
            ->text();
    }
}
