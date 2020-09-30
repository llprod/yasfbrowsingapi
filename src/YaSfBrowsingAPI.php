<?php

namespace YaSfBrowsing;

require dirname(__DIR__) .'/vendor/autoload.php';

Class YaSfBrowsingAPI
{
    private $apiKey;

    const API_BASE = 'https://sba.yandex.net';
    const FIND_PATH = '/v4/threatMatches:find';
    const FIND_ADULT = '/v4/threatMatches:cp';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }


    /**
     * @param string $url
     * @return Match|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function find(string $url): ?Match
    {
        $client = new \GuzzleHttp\Client(['base_uri' => self::API_BASE]);
        $response = $client->request('POST', self::FIND_PATH . '?key=' . $this->apiKey, ['body' => json_encode([
            'client' => ['clientId' => 'YaSfBrowsingAPI', 'clientVersion' => '1'],
            'threatInfo' => [
                'threatTypes' => ['THREAT_TYPE_UNSPECIFIED', 'MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'],
                'platformTypes' => ['ALL_PLATFORMS'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [['url' => $url]]
            ]
        ])]);

        $code = $response->getStatusCode();
        $reason = $response->getReasonPhrase();

        if ($code !== 200 || $reason !== 'OK') {
            throw new \Exception('Error Processing Request, code:' . $code . ', reason phrase:' . $reason, 1);
        }

        $check = (string)$response->getBody();

        if (!$check || $check == '' || $check === '{}') {
            return null;
        }

        $parsed = json_decode($check);
        if (isset($parsed->matches) && $parsed->matches && is_array($parsed->matches)) {
            $elt = $parsed->matches[0];
            $match = new Match($elt->threatType, $elt->threatEntryType, $elt->platformType, $elt->threat->url, $elt->cacheDuration);

            return $match;
        }

        return null;
    }
}
