<?php
class FundaClient extends CComponent
{
    const LOCATION_AMSTERDAM = 'amsterdam';

    const TYPE_SALE_OLD = 'koop';
    const TYPE_REND = 'huur';
    const TYPE_SALE_NEW = 'nieuwbouw';

    const OPTION_GARDEN = 'tuin';
    const OPTION_SAUNA = 'sauna';

    const URL = 'http://partnerapi.funda.nl/feeds/Aanbod.svc/json/';

    private $curl;

    public $key;

    public $fetch_pagesize;

    public function init()
    {
        //use one connection per all requests
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    }

    public function fetch($location, $type, array $options = array(), $page = 1)
    {
        $params = array(
            'type' => $type,
            'zo' => '/' . $location . '/' . join('/', $options),
            'page' => $page,
            'pagesize' => $this->fetch_pagesize,
        );

        curl_setopt($this->curl, CURLOPT_URL, self::URL . $this->key . '/?' . http_build_query($params));
        $output = curl_exec($this->curl);

        if ($output === '') throw new FundaClientExceptionLimitExceeded();

        if (!$output) throw new \FundaClientExceptionServerError;

        $output = json_decode($output, true);
        if (!$output || !is_array($output)) throw new \FundaClientExceptionUnexpectedOutput;
        return $output;
    }

    public function __destruct()
    {
//        curl_close($this->curl);
    }
}
