<?php
namespace YaSfBrowsing;

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

    /**
     * Undocumented, but still working API method
     */
    public function adult($url): bool {
        $client = new \GuzzleHttp\Client(['base_uri' => self::API_BASE]);
        $response = $client->request('GET', 'cp?client=api&pver=4.0&url='.$url);

        return (string)$response->getBody() == 'adult';
    }
}
