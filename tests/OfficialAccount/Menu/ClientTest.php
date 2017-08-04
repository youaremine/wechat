<?php


namespace EasyWeChat\Tests\OfficialAccount\Menu;


use EasyWeChat\OfficialAccount\Menu\Client;
use EasyWeChat\Tests\TestCase;


class ClientTest extends TestCase
{
    public function testLists()
    {
        $client = $this->mockApiClient(Client::class);
        $client->expects()->httpGet('cgi-bin/menu/get')->andReturn('mock-result')->once();

        $this->assertSame('mock-result', $client->lists());
    }

    public function testCurrent()
    {
        $client = $this->mockApiClient(Client::class);
        $client->expects()->httpGet('cgi-bin/get_current_selfmenu_info')->andReturn('mock-result')->once();

        $this->assertSame('mock-result', $client->current());
    }

    public function testCreate()
    {
        $client = $this->mockApiClient(Client::class);

        // with match rule
        $client->expects()->httpPostJson('cgi-bin/menu/addconditional', [
            'button' => ['foo' => 'bar'],
            'matchrule' => ['tag_id' => 1],
        ])->andReturn('mock-result')->once();
        $this->assertSame('mock-result', $client->create(['foo' => 'bar'], ['tag_id' => 1]));


        // without match rule
        $client->expects()->httpPostJson('cgi-bin/menu/create', [
            'button' => ['foo' => 'bar'],
        ])->andReturn('mock-result')->once();
        $this->assertSame('mock-result', $client->create(['foo' => 'bar']));
    }

    public function testDelete()
    {
        $client = $this->mockApiClient(Client::class);

        // without menu id
        $client->expects()->httpGet('cgi-bin/menu/delete')->andReturn('mock-result')->once();
        $this->assertSame('mock-result', $client->delete());


        // without match rule
        $client->expects()->httpPostJson('cgi-bin/menu/delconditional', ['menuid' => 'mock-id'])->andReturn('mock-result')->once();
        $this->assertSame('mock-result', $client->delete('mock-id'));
    }

    public function testMatch()
    {
        $client = $this->mockApiClient(Client::class);

        $client->expects()->httpPostJson('cgi-bin/menu/trymatch', ['user_id' => 'mock-user-id'])->andReturn('mock-result')->once();
        $this->assertSame('mock-result', $client->match('mock-user-id'));
    }
}
