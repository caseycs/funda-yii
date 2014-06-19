<?php
class FundaFetchCommandTest extends PHPUnit_Framework_TestCase
{
    private $rpm_limit = 100;

    public function setUp()
    {
        parent::setUp();

        $this->cache = new CacheMock();
        Yii::app()->setComponent('cache', $this->cache);

        $this->Command = new FundaFetchCommand(1,1);
        $this->Command->page_expire = 100;
        $this->Command->rpm_limit = $this->rpm_limit;;
        $this->Command->pages_limit = 100;
        $this->Command->init();
    }

    public function test_isFundaRequestAvaliable_correct_cache_values()
    {
        ReflectionHelper::call($this->Command, 'isFundaRequestAvaliable');
        $this->assertGreaterThanOrEqual(time() - 1, $this->cache->data[MemCacheKeys::FUNDA_FETCH_REQUESTS_START]);
        $this->assertLessThanOrEqual(time(), $this->cache->data[MemCacheKeys::FUNDA_FETCH_REQUESTS_START]);
        $this->assertEquals(1, $this->cache->data[MemCacheKeys::FUNDA_FETCH_REQUESTS_MADE]);
    }

    public function test_isFundaRequestAvaliable_cache_empty()
    {
        $this->assertTrue(ReflectionHelper::call($this->Command, 'isFundaRequestAvaliable'));
    }

    public function test_isFundaRequestAvaliable_cache_half_empty1()
    {
        $this->cache->set(MemCacheKeys::FUNDA_FETCH_REQUESTS_START, time());
        $this->assertTrue(ReflectionHelper::call($this->Command, 'isFundaRequestAvaliable'));
    }

    public function test_isFundaRequestAvaliable_cache_half_empty2()
    {
        $this->cache->set(MemCacheKeys::FUNDA_FETCH_REQUESTS_MADE, 50);
        $this->assertTrue(ReflectionHelper::call($this->Command, 'isFundaRequestAvaliable'));
    }

    public function test_isFundaRequestAvaliable_cache_half_empty3()
    {
        $this->cache->set(MemCacheKeys::FUNDA_FETCH_REQUESTS_MADE, $this->rpm_limit + 1);
        $this->assertTrue(ReflectionHelper::call($this->Command, 'isFundaRequestAvaliable'));
    }

    public function test_isFundaRequestAvaliable_cache_full_yes()
    {
        $this->cache->set(MemCacheKeys::FUNDA_FETCH_REQUESTS_START, time());
        $this->cache->set(MemCacheKeys::FUNDA_FETCH_REQUESTS_MADE, $this->rpm_limit - 1);
        $this->assertTrue(ReflectionHelper::call($this->Command, 'isFundaRequestAvaliable'));
    }

    public function test_isFundaRequestAvaliable_cache_full_no_limit_reached()
    {
        $this->cache->set(MemCacheKeys::FUNDA_FETCH_REQUESTS_START, time());
        $this->cache->set(MemCacheKeys::FUNDA_FETCH_REQUESTS_MADE, $this->rpm_limit);
        $this->assertFalse(ReflectionHelper::call($this->Command, 'isFundaRequestAvaliable'));
    }

    public function test_isFundaRequestAvaliable_cache_full_yes_time_old_but_limit_reached()
    {
        $this->cache->set(MemCacheKeys::FUNDA_FETCH_REQUESTS_START, time() - 70);
        $this->cache->set(MemCacheKeys::FUNDA_FETCH_REQUESTS_MADE, $this->rpm_limit);
        $this->assertTrue(ReflectionHelper::call($this->Command, 'isFundaRequestAvaliable'));
    }
}
