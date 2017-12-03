<?php

namespace hisorange\BrowserDetect\Test;

use Illuminate\Support\Facades\Blade;

/**
 * Class BladeTest
 *
 * @package hisorange\BrowserDetect\Test
 * @coversDefaultClass \hisorange\BrowserDetect\ServiceProvider
 */
class BladeTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\SkippedTestError
     * @throws \PHPUnit_Framework_SkippedTestError
     */
    public function setUp()
    {
        parent::setUp();

        if (version_compare($this->app->version(), '5.5', '<')) {
            $this->markTestSkipped('Cannot test if directives below laravel 5.5.');
        }
    }

    /**
     * @return array
     */
    public function directiveProvider()
    {
        return [['mobile'], ['desktop'], ['tablet']];
    }

    /**
     * @dataProvider directiveProvider
     * @param string $directive
     * @covers       ::<protected>registerDirectives()
     */
    public function testDirectives($directive)
    {
        $actual   = Blade::compileString('@' . $directive . ' Ok @end' . $directive);
        $expected = "<?php if (\Illuminate\Support\Facades\Blade::check('$directive')): ?> Ok <?php endif; ?>";

        $this->assertSame($expected, $actual);
    }

    /**
     * @covers ::<protected>registerDirectives()
     */
    public function testBrowserDirective()
    {
        $actual   = Blade::compileString('@browser("isMobile") Ok @endbrowser');
        $expected = '<?php if (\Illuminate\Support\Facades\Blade::check(\'browser\', "isMobile")): ?> Ok <?php endif; ?>';

        $this->assertSame($expected, $actual);
    }
}