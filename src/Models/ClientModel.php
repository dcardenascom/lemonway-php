<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/davidcardenasguia
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Models;

use DateTime;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ServerException;
use Lemonway\Exceptions\ApiException;
use stdClass;

/**
 * Class ClientModel.
 */
class ClientModel extends CommonModel
{
    const SERVICE_NAME = 'Service.asmx';
    const DEFAULT_VERSION = '4.0';
    const DEFAULT_LANGUAGE = 'en';
    const METHOD = 'POST';

    /** @var string */
    private $version;
    /** @var string */
    private $language;
    /** @var GuzzleClient */
    private $guzzleClient;
    /** @var string */
    private $ip;
    /** @var CredentialsModel */
    private $lemonwayCredentials;

    /**
     * ClientModel constructor.
     *
     * @param CredentialsModel $lemonwayCredentials
     * @param string           $directKitUrl
     * @param string           $version
     * @param string           $language
     * @param null             $ip
     */
    public function __construct(
        CredentialsModel $lemonwayCredentials,
        string $directKitUrl,
        string $version = self::DEFAULT_VERSION,
        string $language = self::DEFAULT_LANGUAGE,
        $ip = null
    ) {
        $this->guzzleClient = new GuzzleClient(['base_uri' => $directKitUrl]);
        $this->version = $version;
        $this->language = $language;
        $this->lemonwayCredentials = $lemonwayCredentials;
        $this->ip = $ip ?: $this->generateIp();
    }

    /**
     * @throws ApiException
     * @throws \RuntimeException
     *
     * @return bool
     */
    public function checkConnection(): bool
    {
        $currentDate = new DateTime();
        $this->getResponseFromAPI(WalletModel::ACTION_GET_KYC_STATUS, ['updateDate' => $currentDate->getTimestamp()]);

        return true;
    }

    /**
     * @param string $action
     * @param array  $parameters
     *
     * @throws ApiException
     * @throws \RuntimeException
     *
     * @return stdClass
     */
    public function getResponseFromAPI(string $action, array $parameters = []): stdClass
    {
        $mandatoryParameters = [
            'wlLogin'  => $this->lemonwayCredentials->getUsername(),
            'wlPass'   => $this->lemonwayCredentials->getPassword(),
            'language' => $this->getLanguage(),
            'version'  => $this->getVersion(),
            'walletIp' => $this->getIp(),
        ];

        $headers = [
            'Content-type' => 'application/json;charset=utf-8',
        ];

        try {
            $guzzleResponse = $this->guzzleClient->request(
                self::METHOD,
                self::SERVICE_NAME.'/'.$action, [
                'headers' => $headers,
                'json'    => ['p' => array_merge($mandatoryParameters, $parameters)],
            ]);
        } catch (ServerException $e) {
            throw new ApiException($e->getMessage());
        }

        if ($guzzleResponse->getStatusCode() !== 200) {
            throw new ApiException($guzzleResponse->getBody()->getContents());
        }

        $content = json_decode($guzzleResponse->getBody()->getContents());
        $error = $this->getErrorsFromContent($content);

        if ('' !== $error) {
            throw new ApiException($error);
        }

        return $content;
    }

    /**
     * @param stdClass $content
     *
     * @return string
     */
    public function getErrorsFromContent(stdClass $content): string
    {
        $errorMessage = '';

        if (is_object($content) && property_exists($content, 'd')) {
            if (is_object($content->d) && property_exists($content->d, 'E')) {
                if (is_object($content->d->E) && property_exists($content->d->E, 'Msg')) {
                    $errorMessage .= $content->d->E->Msg.': ';
                }
                if (is_object($content->d->E) && property_exists($content->d->E, 'Error')) {
                    $errorMessage .= $content->d->E->Error;
                }
            }
        }

        return $errorMessage;
    }

    /**
     * @return string
     */
    public function generateIp(): string
    {
        $ip = '127.0.0.1';

        $ip = getenv('HTTP_CLIENT_IP') ?: $ip;
        $ip = getenv('HTTP_X_FORWARDED_FOR') ?: $ip;
        $ip = getenv('HTTP_X_FORWARDED') ?: $ip;
        $ip = getenv('HTTP_FORWARDED') ?: $ip;
        $ip = getenv('REMOTE_ADDR') ?: $ip;

        $ip = $_SERVER['HTTP_CLIENT_IP'] ?? $ip;
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $ip;
        $ip = $_SERVER['HTTP_X_FORWARDED'] ?? $ip;
        $ip = $_SERVER['HTTP_FORWARDED'] ?? $ip;
        $ip = $_SERVER['REMOTE_ADDR'] ?? $ip;

        return $ip;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }
}
