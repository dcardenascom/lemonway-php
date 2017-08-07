<?php
/**
 * Created by David Cardenas
 * GitHub: https://github.com/dcardenascom
 * GitLab: https://gitlab.com/dcardenas
 * Site: http://dcardenas.com
 * LinkedIn: https://www.linkedin.com/in/davidcardenasguia/.
 */

namespace Lemonway\Models;

use DateTime;
use Goutte\Client as GouteClient;
use Guzzle\Plugin\Cookie\Cookie;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\ServerException;
use Lemonway\Exceptions\ApiException;
use Lemonway\Exceptions\UnknownException;
use stdClass;
use Symfony\Component\DomCrawler\Link;

/**
 * Class ClientModel.
 */
class ClientModel extends CommonModel
{
    const SERVICE_NAME = 'Service.asmx';
    const DEFAULT_VERSION = '4.0';
    const DEFAULT_LANGUAGE = 'en';
    const METHOD = 'POST';

    const FORM_USERNAME_LABEL = 'username';
    const FORM_PASSWORD_LABEL = 'password';
    const FORM_SUBMIT_BUTTON_LABEL = 'Connexion';
    const CSRF_TOKEN_XPATH = '//*[@id="globalSearchForm"]/div/input';

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

    /**
     * @param string $baseUrl
     * @param string $username
     * @param string $password
     *
     * @throws UnknownException
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getCsrfToken(string $baseUrl, string $username, string $password): string
    {
        try {
            $gouteClient = new GouteClient();
            $crawler = $gouteClient->request('GET', $baseUrl);
            $form = $crawler->selectButton('Connexion')->form();
            $crawler = $gouteClient->submit($form, ['username' => $username, 'password' => $password]);
            $csrfToken = $crawler->filterXPath('//*[@id="globalSearchForm"]/div/input')->attr('value');
        } catch (\Exception $e) {
            throw new UnknownException('Error trying to get the csrf_token. Please check the username and password used');
        }

        return $csrfToken;
    }

    /**
     * @param string $backofficeBaseUrl
     * @param int $walletProviderId
     * @param int $documentProviderId
     * @return \Psr\Http\Message\StreamInterface
     * @throws UnknownException
     */
    public function downloadDocumentFile(string $backofficeBaseUrl, int $walletProviderId, int $documentProviderId)
    {
        try {
            $gouteClient = new GouteClient();
            $crawler = $gouteClient->request('GET', $backofficeBaseUrl);
            $form = $crawler->selectButton(self::FORM_SUBMIT_BUTTON_LABEL)->form();
            $crawler = $gouteClient->submit($form, [
                self::FORM_USERNAME_LABEL => $this->lemonwayCredentials->getUsername(),
                self::FORM_PASSWORD_LABEL => $this->lemonwayCredentials->getPassword()
            ]);
            $csrfToken = $crawler->filterXPath(self::CSRF_TOKEN_XPATH)->attr('value');
            $documentFileUrl = $backofficeBaseUrl . '/scripts/showDocument.php' .
                '?user_id=' . $walletProviderId .
                '&doc_id=' . $documentProviderId .
                '&csrf_token=' . $csrfToken;

            $cookies = $gouteClient->getCookieJar();
            /** @var \Symfony\Component\BrowserKit\Cookie $values */
            $cookieValues = $cookies->all()[0];

            $guzzleCookieJar = new CookieJar();
            $guzzleCookie = new SetCookie();
            $guzzleCookie->setName($cookieValues->getName());
            $guzzleCookie->setValue($cookieValues->getValue());
            $guzzleCookie->setExpires($cookieValues->getExpiresTime());
            $guzzleCookie->setPath($cookieValues->getPath());
            $guzzleCookie->setDomain($cookieValues->getDomain());
            $guzzleCookie->setSecure($cookieValues->isSecure());
            $guzzleCookie->setHttpOnly($cookieValues->isHttpOnly());
            $guzzleCookieJar->setCookie($guzzleCookie);

            $guzzleClient = new GuzzleClient([
                'timeout' => 900,
                'verify' => false,
                'cookies' => $guzzleCookieJar
            ]);

            $request = $guzzleClient->request('GET', $documentFileUrl);

            return $request->getBody();

        } catch (\Exception $e) {
            throw new UnknownException('Error trying to get the csrf_token. Please check the username and password used');
        }
    }
}
