<?php

/**
 * Test class to test rcube_spellchecker class
 *
 * @package Tests
 */
class Framework_Spellchecker extends PHPUnit\Framework\TestCase
{
    /**
     * Test languages() method
     */
    function test_languages()
    {
        $object = new rcube_spellchecker();

        $langs = $object->languages();

        $this->assertSame('English (US)', $langs['en_US']);
    }

    /**
     * Test check() method
     */
    function test_check()
    {
        if (!extension_loaded('pspell')) {
            $this->markTestSkipped();
        }

        rcube::get_instance()->config->set('spellcheck_engine', 'pspell');

        $object = new rcube_spellchecker();

        $this->assertTrue($object->check('one'));

        // Test other methods that depend on the spellcheck result
        $this->assertSame(0, $object->found());
        $this->assertSame([], $object->get_words());

        $this->assertSame(
            '<?xml version="1.0" encoding="UTF-8"?><spellresult charschecked="3"></spellresult>',
            $object->get_xml()
        );

        $this->assertFalse($object->check('ony'));

        // Test other methods that depend on the spellcheck result
        $this->assertSame(1, $object->found());
        $this->assertSame(['ony'], $object->get_words());

        $this->assertSame(
            '<?xml version="1.0" encoding="UTF-8"?><spellresult charschecked="3">'
            . '<c o="0" l="3">' . "ON\ton\tOnt\tonly\tonya\tNY\tonyx\tOno\tany\tone</c>"
            . '</spellresult>',
            $object->get_xml()
        );
    }

    /**
     * Test get_suggestions() method
     */
    function test_get_suggestions()
    {
        if (!extension_loaded('pspell')) {
            $this->markTestSkipped();
        }

        rcube::get_instance()->config->set('spellcheck_engine', 'pspell');

        $object = new rcube_spellchecker();

        $expected = ['ON','on','Ont','only','onya','NY','onyx','Ono','any','one'];
        $this->assertSame($expected, $object->get_suggestions('ony'));
    }

    /**
     * Test get_words() method
     */
    function test_get_words()
    {
        if (!extension_loaded('pspell')) {
            $this->markTestSkipped();
        }

        rcube::get_instance()->config->set('spellcheck_engine', 'pspell');

        $object = new rcube_spellchecker();

        $this->assertSame(['ony'], $object->get_words('ony'));
    }

    /**
     * Test is_exception() method
     */
    function test_is_exception()
    {
        $object = new rcube_spellchecker();

        $this->assertFalse($object->is_exception('test'));

        $this->assertTrue($object->is_exception('9'));

        // TODO: Test other cases and dictionary
    }

    /**
     * Test add_word() method
     */
    function test_add_word()
    {
        $this->markTestIncomplete();
    }

    /**
     * Test remove_word() method
     */
    function test_remove_word()
    {
        $this->markTestIncomplete();
    }
}
