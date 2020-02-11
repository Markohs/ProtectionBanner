<?php

namespace Tests;

class ProtectionBannerTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::SetUp();

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function unconfigured_remains_inactive()
    {
        $response = $this->get('test');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function can_force_redirect_single_route()
    {
        $response = $this->get('test_forced');

        $response->assertStatus(200);

        config()->set('protectionbanner.enabled_environments', ['testing']);

        $response2 = $this->get('test_forced');

        $response2->assertStatus(406);


    }

    /**
     * @test
     */
    public function can_force_redirect_single_route_whitelist()
    {
        config()->set('protectionbanner.enabled_environments', ['testing']);

        $response = $this->get('test_forced');

        config()->set('protectionbanner.whitelist', []);

        $response->assertStatus(406);

        config()->set('protectionbanner.whitelist', ['test_forced']);
        $response2 = $this->get('test_forced');
        $response2->assertStatus(200);
    }

    /**
     * @test
     */
    public function autoregister_works()
    {
        // TODO: No idea how to check this one, as it's executed on servide provider booting
        // Maybe update forcehttps.autoregister and re-boot the service provider? No idea.
        $this->assertTrue(true);
    }

    /** @test */
    public function populates_expected_config_settings()
    {
        $this->assertEquals(['stage', 'prod', 'production'], $this->app['config']['protectionbanner.enabled_environments']);
        $this->assertEquals([], $this->app['config']['protectionbanner.whitelist']);
        $this->assertEquals(['web'], $this->app['config']['protectionbanner.autoregister']);
    }
}
