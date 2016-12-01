<?php

use PHPUnit\Framework\TestCase;

class XMLTest extends TestCase
{
    use Codeception\Specify;

    private function createParser($fixture)
    {
        $content = new Presskit\Content;
        return new Presskit\Parser\XML(__DIR__ . '/../fixtures/' . $fixture . '.xml', $content);
    }

    public function testParsing()
    {
        $this->specify('parse returns the content object', function () {
            $XMLParser = $this->createParser('normal');
            verify($XMLParser->parse())->isInstanceOf('Presskit\Content');
        });

        $this->specify('it can handle a empty xml file', function () {
            $XMLParser = $this->createParser('empty');
            verify($XMLParser->parse())->isInstanceOf('Presskit\Content');
        });
    }

    public function testTitleParsing()
    {
        $this->specify('the title is read from the xml file', function () {
            $XMLParser = $this->createParser('normal');
            verify($XMLParser->parse()->getTitle())->equals('Example Title');
        });

        $this->specify('it can handle a missing title tag', function () {
            $XMLParser = $this->createParser('empty');
            verify($XMLParser->parse()->getTitle())->false();
        });
    }
}
